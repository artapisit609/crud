import sqlite3
from kivy.app import App
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.label import Label
from kivy.uix.textinput import TextInput
from kivy.uix.button import Button
from kivy.uix.popup import Popup
from kivy.uix.scrollview import ScrollView

# สร้างหรือเชื่อมต่อกับฐานข้อมูล SQLite
conn = sqlite3.connect('users.db')
c = conn.cursor()

# สร้างตารางในฐานข้อมูลถ้ายังไม่มี
c.execute('''
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        username TEXT UNIQUE,
        password TEXT,
        role TEXT DEFAULT 'user'
    )
''')
conn.commit()

class LoginScreen(BoxLayout):
    def __init__(self, **kwargs):
        super(LoginScreen, self).__init__(**kwargs)
        self.orientation = 'vertical'

        # ช่องกรอกข้อมูลสำหรับ Username
        self.add_widget(Label(text='Username:'))
        self.username_input = TextInput(multiline=False)
        self.add_widget(self.username_input)

        # ช่องกรอกข้อมูลสำหรับ Password
        self.add_widget(Label(text='Password:'))
        self.password_input = TextInput(multiline=False, password=True)
        self.add_widget(self.password_input)

        # ปุ่มสำหรับ Login
        self.login_button = Button(text='Login')
        self.login_button.bind(on_press=self.login)
        self.add_widget(self.login_button)

        # ปุ่มสำหรับ Register
        self.register_button = Button(text='Register')
        self.register_button.bind(on_press=self.show_registration_form)
        self.add_widget(self.register_button)

        # ปุ่มสำหรับ Forgot Password
        self.forgot_password_button = Button(text='Forgot Password')
        self.forgot_password_button.bind(on_press=self.show_forgot_password_form)
        self.add_widget(self.forgot_password_button)

        # ตัวแปรเก็บสถานะผู้ใช้หลังเข้าสู่ระบบ
        self.logged_in_user = None

    def login(self, instance):
        username = self.username_input.text
        password = self.password_input.text

        # ตรวจสอบข้อมูลในฐานข้อมูล SQLite
        c.execute("SELECT * FROM users WHERE username=? AND password=?", (username, password))
        result = c.fetchone()

        if result:
            self.logged_in_user = result
            self.show_popup("Login Successful", "Welcome back!")
            if self.logged_in_user[3] == 'admin':
                self.show_admin_options()
        else:
            self.show_popup("Login Failed", "Invalid username or password.")

    def show_registration_form(self, instance):
        # สร้างและแสดงฟอร์มสำหรับการสมัครสมาชิก
        self.clear_widgets()

        self.add_widget(Label(text='Register New Account'))
        self.add_widget(Label(text='Username:'))
        self.new_username_input = TextInput(multiline=False)
        self.add_widget(self.new_username_input)

        self.add_widget(Label(text='Password:'))
        self.new_password_input = TextInput(multiline=False, password=True)
        self.add_widget(self.new_password_input)

        self.submit_registration_button = Button(text='Submit Registration')
        self.submit_registration_button.bind(on_press=self.register)
        self.add_widget(self.submit_registration_button)

        self.back_to_login_button = Button(text='Back to Login')
        self.back_to_login_button.bind(on_press=self.back_to_login)
        self.add_widget(self.back_to_login_button)

    def register(self, instance):
        username = self.new_username_input.text
        password = self.new_password_input.text

        try:
            # เพิ่มข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล SQLite
            c.execute("INSERT INTO users (username, password) VALUES (?, ?)", (username, password))
            conn.commit()
            self.show_popup("Registration Successful", "Account created successfully.")
            self.back_to_login(instance)
        except sqlite3.IntegrityError:
            self.show_popup("Registration Failed", "Username already exists.")

    def show_forgot_password_form(self, instance):
        # สร้างและแสดงฟอร์มสำหรับการรีเซ็ตรหัสผ่าน
        self.clear_widgets()

        self.add_widget(Label(text='Reset Password'))
        self.add_widget(Label(text='Username:'))
        self.reset_username_input = TextInput(multiline=False)
        self.add_widget(self.reset_username_input)

        self.add_widget(Label(text='New Password:'))
        self.new_password_input = TextInput(multiline=False, password=True)
        self.add_widget(self.new_password_input)

        self.submit_reset_button = Button(text='Submit New Password')
        self.submit_reset_button.bind(on_press=self.reset_password)
        self.add_widget(self.submit_reset_button)

        self.back_to_login_button = Button(text='Back to Login')
        self.back_to_login_button.bind(on_press=self.back_to_login)
        self.add_widget(self.back_to_login_button)

    def reset_password(self, instance):
        username = self.reset_username_input.text
        new_password = self.new_password_input.text

        # ตรวจสอบว่า username มีอยู่ในระบบหรือไม่
        c.execute("SELECT * FROM users WHERE username=?", (username,))
        result = c.fetchone()

        if result:
            # อัปเดตรหัสผ่านใหม่ในฐานข้อมูล SQLite
            c.execute("UPDATE users SET password=? WHERE username=?", (new_password, username))
            conn.commit()
            self.show_popup("Password Reset Successful", "Your password has been updated.")
            self.back_to_login(instance)
        else:
            self.show_popup("Password Reset Failed", "Username does not exist.")

    def show_admin_options(self):
        # แสดงปุ่มเพื่อดูรายชื่อผู้ใช้ทั้งหมดหากผู้ใช้เป็น admin
        self.clear_widgets()

        self.add_widget(Label(text=f"Welcome, {self.logged_in_user[1]}"))
        view_users_button = Button(text='View All Users')
        view_users_button.bind(on_press=self.show_users_list)
        self.add_widget(view_users_button)

        self.logout_button = Button(text='Logout')
        self.logout_button.bind(on_press=self.logout)
        self.add_widget(self.logout_button)

    def show_users_list(self, instance):
        # สร้างและแสดงหน้ารายชื่อผู้ใช้ทั้งหมด (สำหรับ admin เท่านั้น)
        self.clear_widgets()

        self.add_widget(Label(text='List of All Users'))

        scroll_view = ScrollView(size_hint=(1, None), size=(400, 300))
        users_layout = BoxLayout(orientation='vertical', size_hint_y=None)
        users_layout.bind(minimum_height=users_layout.setter('height'))

        c.execute("SELECT username, role FROM users")
        users = c.fetchall()

        for user in users:
            user_label = Label(text=f"Username: {user[0]}, Role: {user[1]}", size_hint_y=None, height=40)
            users_layout.add_widget(user_label)

        scroll_view.add_widget(users_layout)
        self.add_widget(scroll_view)

        back_button = Button(text='Back')
        back_button.bind(on_press=self.show_admin_options)
        self.add_widget(back_button)

    def logout(self, instance):
        # ล้างข้อมูลผู้ใช้ที่ล็อกอิน และกลับไปยังหน้าจอ Login
        self.logged_in_user = None
        self.back_to_login(instance)

    def back_to_login(self, instance):
        # กลับไปยังหน้าจอ Login
        self.clear_widgets()
        self.__init__()

    def show_popup(self, title, message):
        # แสดง Popup แจ้งเตือน
        popup_layout = BoxLayout(orientation='vertical')
        popup_label = Label(text=message)
        popup_button = Button(text='OK')
        popup_layout.add_widget(popup_label)
        popup_layout.add_widget(popup_button)
        popup = Popup(title=title, content=popup_layout, size_hint=(None, None), size=(300, 200))
        popup_button.bind(on_press=popup.dismiss)
        popup.open()

class LoginApp(App):
    def build(self):
        return LoginScreen()

if __name__ == '__main__':
    LoginApp().run()

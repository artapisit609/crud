// ignore_for_file: library_private_types_in_public_api

import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class Employee {
  final String code;
  final String name;
  final String surname;
  final String startDate;

  Employee({
    required this.code,
    required this.name,
    required this.surname,
    required this.startDate,
  });

  factory Employee.fromJson(Map<String, dynamic> json) {
    return Employee(
      code: json['code'] ?? '',
      name: json['name'] ?? '',
      surname: json['surname'] ?? '',
      startDate: json['start_date'] ?? '',
    );
  }

  // ฟังก์ชันคำนวณอายุงาน
  String calculateYearsMonthsDaysOfService() {
    try {
      final startDate = DateTime.parse(this.startDate);
      final now = DateTime.now();

      int years = now.year - startDate.year;
      int months = now.month - startDate.month;
      int days = now.day - startDate.day;

      if (days < 0) {
        months--;
        days += DateTime(now.year, now.month, 0).day; // จำนวนวันในเดือนก่อนหน้า
      }

      if (months < 0) {
        years--;
        months += 12;
      }

      return '$years ปี $months เดือน $days วัน';
    } catch (e) {
      return 'ไม่สามารถคำนวณได้';
    }
  }
}

class Point {
  final String total;
  final String ptotal;
  final String attendance;
  final String grade;

  Point({
    required this.total,
    required this.ptotal,
    required this.attendance,
    required this.grade,
  });

  factory Point.fromJson(Map<String, dynamic> json) {
    return Point(
      total: json['total'] ?? '-',
      ptotal: json['ptotal'] ?? '-',
      attendance: json['attendance'] ?? '-',
      grade: json['grade'] ?? '-',
    );
  }
}

Future<Map<String, dynamic>> fetchEmployeeData(String employeeCode) async {
  final response = await http.get(Uri.parse(
      'http://10.10.22.88/crud/api_profile.php?employee_code=$employeeCode'));

  if (response.statusCode == 200) {
    try {
      final data = json.decode(response.body);

      if (data.containsKey('error')) {
        throw Exception(data['error']);
      }

      if (data is Map<String, dynamic>) {
        return data;
      } else {
        throw Exception('Unexpected data format');
      }
    } catch (e) {
      throw Exception('Failed to parse JSON: $e');
    }
  } else {
    throw Exception('Failed to load employee data');
  }
}

class EmployeeProfilePage extends StatefulWidget {
  final String employeeCode;

  const EmployeeProfilePage({super.key, required this.employeeCode});

  @override
  _EmployeeProfilePageState createState() => _EmployeeProfilePageState();
}

class _EmployeeProfilePageState extends State<EmployeeProfilePage> {
  int _selectedIndex = 0;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('โปรไฟล์พนักงาน')),
      body: FutureBuilder<Map<String, dynamic>>(
        future: fetchEmployeeData(widget.employeeCode),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          } else if (snapshot.hasData) {
            final data = snapshot.data!;
            final employeeData =
                data['employee'] as Map<String, dynamic>? ?? {};
            final employee = Employee.fromJson(employeeData);

            final pointsData = data['points'] as Map<String, dynamic>? ?? {};
            final point2301List =
                pointsData['point2301'] as List<dynamic>? ?? [];
            final point2302List =
                pointsData['point2302'] as List<dynamic>? ?? [];
            final point2401List =
                pointsData['point2401'] as List<dynamic>? ?? [];

            final points2301 = point2301List.isNotEmpty
                ? Point.fromJson(
                    point2301List[0] as Map<String, dynamic>? ?? {})
                : Point(total: '-', ptotal: '-', attendance: '-', grade: '-');
            final points2302 = point2302List.isNotEmpty
                ? Point.fromJson(
                    point2302List[0] as Map<String, dynamic>? ?? {})
                : Point(total: '-', ptotal: '-', attendance: '-', grade: '-');
            final points2401 = point2401List.isNotEmpty
                ? Point.fromJson(
                    point2401List[0] as Map<String, dynamic>? ?? {})
                : Point(total: '-', ptotal: '-', attendance: '-', grade: '-');

            final attendances =
                data['attendances'] as Map<String, dynamic>? ?? {};
            final atten23 = attendances['atten23'] as List<dynamic>? ?? [];
            final atten24 = attendances['atten24'] as List<dynamic>? ?? [];

            return IndexedStack(
              index: _selectedIndex,
              children: [
                _buildGeneralInfo(employee),
                _buildEvaluation(points2301, points2302, points2401),
                _buildLeaveInfo(atten23, atten24),
              ],
            );
          } else {
            return const Center(child: Text('No data available'));
          }
        },
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) {
          setState(() {
            _selectedIndex = index;
          });
        },
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: 'ข้อมูลทั่วไป',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.assessment),
            label: 'คะแนนประเมิน',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.beach_access),
            label: 'การลางาน',
          ),
        ],
      ),
    );
  }

  Widget _buildGeneralInfo(Employee employee) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _buildEmployeeCard('รหัสพนักงาน', employee.code),
          const SizedBox(height: 16),
          _buildEmployeeCard('ชื่อ', '${employee.name} ${employee.surname}'),
          const SizedBox(height: 16),
          _buildEmployeeCard('วันที่เริ่มงาน', employee.startDate),
          const SizedBox(height: 16),
          _buildEmployeeCard(
              'อายุงาน',
              employee
                  .calculateYearsMonthsDaysOfService()), // เพิ่มการแสดงอายุงาน
        ],
      ),
    );
  }

  Widget _buildEmployeeCard(String title, String content) {
    return Card(
      elevation: 5,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Row(
          children: [
            Expanded(
              child: Text(
                title,
                style:
                    const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
              ),
            ),
            Text(
              content,
              style: const TextStyle(fontSize: 16),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEvaluation(
      Point points2301, Point points2302, Point points2401) {
    return ListView(
      padding: const EdgeInsets.all(16.0),
      children: [
        const Text('คะแนนประเมิน',
            style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
        const SizedBox(height: 16),
        _buildEvaluationCard('2301', points2301),
        const SizedBox(height: 16),
        _buildEvaluationCard('2302', points2302),
        const SizedBox(height: 16),
        _buildEvaluationCard('2401', points2401),
        const Center(child: SizedBox(height: 16.0)),
        const Text('**การประเมินนี้เป็นเพียงส่วนของแผนกเท่านั้น**',
            style: TextStyle(fontWeight: FontWeight.bold)),
        const Center(child: SizedBox(height: 16.0)),
        const Text('## ไม่ได้มีผลในการตัดเกรด 100% ##'),
        const Center(child: SizedBox(height: 16.0)),
        const Text('## ขึ้นอยู่กับการพิจารณาของบริษัทอีกครั้ง ##'),
        const Center(child: SizedBox(height: 16.0)),
        const Text('## ข้อมูลใช้เพื่อพิจารณาและพัฒนาส่วนบุคคล ##'),
      ],
    );
  }

  Widget _buildEvaluationCard(String period, Point point) {
    return Card(
      elevation: 5,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'รอบการประเมิน: $period',
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            _buildRow('คะแนนรวม', point.total),
            _buildRow('ทักษะ/ผลงาน(60)', point.ptotal),
            _buildRow('การมาทำงาน(40)', point.attendance),
            _buildRow('เกรด', point.grade),
          ],
        ),
      ),
    );
  }

  Widget _buildRow(String title, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(title, style: const TextStyle(fontSize: 16)),
          Text(value, style: const TextStyle(fontSize: 16)),
        ],
      ),
    );
  }

  Widget _buildLeaveInfo(List<dynamic> atten23, List<dynamic> atten24) {
    final leaveTypes = [
      'ป่วย',
      'ป่วยโควิด',
      'กิจ',
      'กิจในสิทธิ์',
      'พักร้อน',
      'พักร้อนฉุกเฉิน',
    ];

    // Ensure that the map is correctly typed as <String, dynamic>
    final leaveData23 = {
      for (var item in atten23)
        item['type_atten'].toString(): item['total_days']
    };
    final leaveData24 = {
      for (var item in atten24)
        item['type_atten'].toString(): item['total_days']
    };

    return ListView(
      padding: const EdgeInsets.all(16.0),
      children: [
        _buildYearLeaveCard('2023', leaveTypes, leaveData23),
        const SizedBox(height: 16),
        _buildYearLeaveCard('2024', leaveTypes, leaveData24),
      ],
    );
  }

  Widget _buildYearLeaveCard(
      String year, List<String> leaveTypes, Map<String, dynamic> leaveData) {
    return Card(
      elevation: 5,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'ข้อมูลการลางาน ปี $year',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            ...leaveTypes.map((leaveType) {
              final days = leaveData[leaveType] ?? '0';
              return Padding(
                padding: const EdgeInsets.symmetric(vertical: 4.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(leaveType, style: const TextStyle(fontSize: 16)),
                    Text(days.toString(), style: const TextStyle(fontSize: 16)),
                  ],
                ),
              );
            }),
          ],
        ),
      ),
    );
  }
}

void main() {
  runApp(const MaterialApp(
    home: EmployeeProfilePage(employeeCode: '12345'),
    debugShowCheckedModeBanner: false,
  ));
}

function scrollToTop() {
    window.scrollTo({top: 0, behavior: 'smooth'});
}

// ฟังก์ชั่นสำหรับเลื่อนไปยังด้านล่างสุด
function scrollToBottom() {
    window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});
}

// ฟังก์ชั่นสำหรับการแสดง/ซ่อนปุ่ม
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("scrollTopBtn").style.display = "block";
        document.getElementById("scrollBottomBtn").style.display = "block";
    } else {
        document.getElementById("scrollTopBtn").style.display = "none";
        document.getElementById("scrollBottomBtn").style.display = "none";
    }
}
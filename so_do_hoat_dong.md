# Danh sách 31 Sơ đồ hoạt động (Activity Diagrams)

Dưới đây là mã PlantUML cho toàn bộ 31 chức năng dựa theo Ma trận phân quyền.
Bạn copy từng đoạn code (từ @startuml đến @enduml) dán vào trang https://www.plantuml.com/plantuml/uml/ để lấy ảnh nhé.

---

### 1. Xem trang chủ, danh mục, sản phẩm, bài viết
```plantuml
@startuml
|Người dùng|
start
:Truy cập website;
|Hệ thống|
:Truy xuất CSDL (Banner, Sản phẩm, Danh mục);
:Hiển thị giao diện trang chủ;
|Người dùng|
:Xem thông tin;
stop
@enduml
```

### 2. Tìm kiếm, lọc sản phẩm
```plantuml
@startuml
|Người dùng|
start
:Nhập từ khóa / Chọn bộ lọc giá, thương hiệu;
:Nhấn Tìm kiếm;
|Hệ thống|
:Xử lý yêu cầu truy vấn;
if (Có sản phẩm khớp?) then (Có)
  :Hiển thị danh sách sản phẩm;
else (Không)
  :Hiển thị thông báo "Không tìm thấy";
endif
|Người dùng|
:Xem kết quả;
stop
@enduml
```

### 3. Tương tác hỏi đáp với Chatbot AI
```plantuml
@startuml
|Người dùng|
start
:Mở khung chat AI;
:Nhập câu hỏi;
|Hệ thống|
:Phân tích câu hỏi;
:Truy vấn dữ liệu / API AI;
:Sinh ra câu trả lời;
:Hiển thị lên khung chat;
|Người dùng|
:Đọc câu trả lời;
stop
@enduml
```

### 4. Đăng ký nhận bản tin khuyến mãi qua Email
```plantuml
@startuml
|Người dùng|
start
:Nhập địa chỉ Email vào form;
:Nhấn "Đăng ký";
|Hệ thống|
:Kiểm tra định dạng Email;
if (Hợp lệ & Chưa đăng ký?) then (Đúng)
  :Lưu Email vào CSDL (Subscribers);
  :Hiển thị thông báo thành công;
else (Sai/Đã tồn tại)
  :Hiển thị lỗi;
endif
stop
@enduml
```

### 5. Đăng ký tài khoản thành viên mới
```plantuml
@startuml
|Người dùng|
start
:Mở form Đăng ký;
:Nhập thông tin (Tên, Email, Mật khẩu);
:Nhấn "Đăng ký";
|Hệ thống|
:Validate dữ liệu;
if (Email đã tồn tại?) then (Có)
  :Báo lỗi "Email đã được sử dụng";
else (Không)
  :Tạo tài khoản mới;
  :Lưu vào CSDL;
  :Thông báo thành công & Chuyển sang Đăng nhập;
endif
stop
@enduml
```

### 6. Đăng nhập hệ thống mua sắm (Khách hàng)
```plantuml
@startuml
|Khách hàng|
start
:Mở form Đăng nhập;
:Nhập Email và Mật khẩu;
|Hệ thống|
:Kiểm tra thông tin;
if (Đúng mật khẩu & tài khoản User?) then (Đúng)
  :Tạo Session đăng nhập;
  :Chuyển hướng về Trang chủ/Tài khoản;
else (Sai)
  :Hiển thị lỗi "Sai thông tin đăng nhập";
endif
stop
@enduml
```

### 7. Xem và quản lý giỏ hàng cá nhân
```plantuml
@startuml
|Thành viên|
start
:Bấm vào biểu tượng Giỏ hàng;
|Hệ thống|
:Lấy dữ liệu giỏ hàng từ CSDL;
:Hiển thị danh sách sản phẩm & Tổng tiền;
|Thành viên|
if (Thao tác?) then (Thay đổi số lượng)
  |Hệ thống|
  :Cập nhật CSDL & Tính lại tiền;
else (Xóa sản phẩm)
  |Hệ thống|
  :Xóa khỏi CSDL & Tính lại tiền;
endif
stop
@enduml
```

### 8. Thêm/Xóa sản phẩm vào Danh sách yêu thích
```plantuml
@startuml
|Thành viên|
start
:Xem chi tiết sản phẩm;
:Nhấn nút "Trái tim" (Yêu thích);
|Hệ thống|
:Kiểm tra CSDL (Wishlists);
if (Đã có trong danh sách?) then (Có)
  :Xóa sản phẩm khỏi danh sách;
  :Đổi màu nút;
else (Chưa có)
  :Thêm sản phẩm vào danh sách;
  :Đổi màu nút;
endif
stop
@enduml
```

### 9. Đặt hàng và áp dụng mã giảm giá
*(Đã vẽ chi tiết ở phần trước có VNPay, đây là bản tóm gọn)*
```plantuml
@startuml
|Thành viên|
start
:Vào trang Checkout;
:Nhập thông tin nhận hàng;
:Nhập mã giảm giá;
|Hệ thống|
:Kiểm tra & Áp dụng mã giảm giá;
|Thành viên|
:Chọn phương thức thanh toán;
:Nhấn "Đặt hàng";
|Hệ thống|
:Lưu Đơn hàng vào CSDL;
:Gửi Email xác nhận;
:Hiển thị thông báo thành công;
stop
@enduml
```

### 10. Theo dõi trạng thái đơn hàng, xem lịch sử mua hàng
```plantuml
@startuml
|Thành viên|
start
:Truy cập mục "Đơn hàng của tôi";
|Hệ thống|
:Truy vấn CSDL lấy danh sách đơn hàng;
:Trả về giao diện;
|Thành viên|
:Chọn 1 đơn hàng cụ thể;
|Hệ thống|
:Hiển thị chi tiết & Trạng thái hiện tại;
stop
@enduml
```

### 11. Viết đánh giá, bình luận cho sản phẩm đã mua
```plantuml
@startuml
|Thành viên|
start
:Truy cập Đơn hàng đã giao thành công;
:Chọn sản phẩm & Nhấn "Đánh giá";
:Chọn số sao & Nhập nội dung;
:Nhấn "Gửi";
|Hệ thống|
:Kiểm tra tính hợp lệ;
:Lưu vào CSDL (Reviews);
:Hiển thị thông báo thành công;
stop
@enduml
```

### 12. Gửi yêu cầu hủy đơn hàng, hoàn trả hàng
```plantuml
@startuml
|Thành viên|
start
:Truy cập chi tiết đơn hàng;
if (Trạng thái đơn?) then (Chờ duyệt)
  :Nhấn "Hủy đơn";
  |Hệ thống|
  :Cập nhật trạng thái = "Đã hủy";
else (Đã giao)
  |Thành viên|
  :Nhấn "Yêu cầu hoàn trả";
  :Nhập lý do & Hình ảnh;
  |Hệ thống|
  :Tạo bản ghi Refund_Requests;
  :Chờ Admin duyệt;
endif
stop
@enduml
```

### 13. Đăng nhập hệ thống Quản trị (Admin)
```plantuml
@startuml
|Admin|
start
:Vào đường dẫn /admin/login;
:Nhập tài khoản Admin;
|Hệ thống|
:Xác thực Role = Admin;
if (Đúng?) then (Có)
  :Truy cập Dashboard Quản trị;
else (Sai)
  :Báo lỗi truy cập từ chối;
endif
stop
@enduml
```

### 14 & 15 & 17. Quản lý (Thêm/Sửa/Xóa) Danh mục/Sản phẩm/Tin tức/Mã giảm giá
*(Quy trình CRUD chung cho Admin)*
```plantuml
@startuml
|Admin|
start
:Truy cập trang Quản lý (Sản phẩm/Danh mục...);
|Hệ thống|
:Hiển thị danh sách dữ liệu;
|Admin|
:Chọn hành động (Thêm/Sửa/Xóa);
:Nhập thông tin vào Form;
:Nhấn "Lưu";
|Hệ thống|
:Validate dữ liệu;
:Cập nhật vào CSDL;
:Trả về thông báo thành công;
stop
@enduml
```

### 16. Quản lý Kho hàng, xem lịch sử nhập/xuất kho
```plantuml
@startuml
|Admin|
start
:Truy cập mục "Quản lý tồn kho";
|Hệ thống|
:Hiển thị danh sách sản phẩm & số lượng tồn;
|Admin|
:Nhấn "Xem lịch sử (Inventory History)";
|Hệ thống|
:Truy xuất lịch sử nhập/xuất/bán hàng;
:Hiển thị giao diện;
stop
@enduml
```

### 18. Quản lý và kiểm duyệt đánh giá của khách hàng
```plantuml
@startuml
|Admin|
start
:Truy cập mục "Đánh giá sản phẩm";
|Hệ thống|
:Hiển thị danh sách đánh giá;
|Admin|
:Chọn một đánh giá;
if (Nội dung hợp lệ?) then (Có)
  :Duyệt hiển thị;
else (Vi phạm)
  :Nhấn "Ẩn / Xóa";
  |Hệ thống|
  :Cập nhật trạng thái trong CSDL;
endif
stop
@enduml
```

### 19. Thiết lập Chatbot AI, xem lịch sử chat
```plantuml
@startuml
|Admin|
start
:Truy cập mục "Quản lý Chatbot AI";
|Hệ thống|
:Hiển thị các phiên chat của khách hàng;
|Admin|
:Click xem nội dung phiên chat;
|Hệ thống|
:Hiển thị chi tiết (Khách hỏi - AI trả lời);
|Admin|
:Bật/Tắt tính năng AI bằng nút Toggle;
|Hệ thống|
:Cập nhật cấu hình hệ thống;
stop
@enduml
```

### 20. Quản lý người dùng, khóa/mở tài khoản
```plantuml
@startuml
|Admin|
start
:Truy cập mục "Quản lý Người dùng";
|Hệ thống|
:Hiển thị danh sách Users;
|Admin|
:Chọn 1 User vi phạm;
:Nhấn "Khóa tài khoản";
|Hệ thống|
:Đổi trạng thái User thành Inactive;
:Hủy session hiện tại của User;
stop
@enduml
```

### 21. Quản lý Đơn hàng toàn hệ thống, xử lý Hủy/Hoàn
```plantuml
@startuml
|Admin|
start
:Truy cập "Quản lý Đơn hàng";
|Hệ thống|
:Hiển thị danh sách đơn chờ duyệt / yêu cầu hoàn hàng;
|Admin|
:Xem chi tiết;
if (Thao tác?) then (Duyệt đơn)
  |Hệ thống|
  :Chuyển trạng thái "Đã duyệt";
else (Xử lý hoàn hàng)
  |Admin|
  :Chấp nhận / Từ chối hoàn trả;
  |Hệ thống|
  :Cập nhật trạng thái & Hoàn tiền;
endif
stop
@enduml
```

### 22. Điều phối, phân công đơn hàng cho Shipper
```plantuml
@startuml
|Admin|
start
:Chọn các đơn hàng "Đã duyệt";
:Nhấn "Phân công Shipper";
:Chọn Shipper từ Dropdown;
|Hệ thống|
:Cập nhật shipper_id vào Đơn hàng;
:Gửi Notification cho Shipper;
stop
@enduml
```

### 23. Xem biểu đồ thống kê, báo cáo doanh thu
```plantuml
@startuml
|Admin|
start
:Truy cập Dashboard / Báo cáo;
:Chọn khoảng thời gian (Từ ngày - Đến ngày);
|Hệ thống|
:Query tính toán tổng doanh thu, số đơn;
:Vẽ biểu đồ (Chart);
:Hiển thị lên màn hình;
stop
@enduml
```

### 24. Đăng nhập hệ thống Giao hàng (Shipper)
```plantuml
@startuml
|Shipper|
start
:Truy cập cổng Shipper (/shipper/login);
:Nhập tài khoản;
|Hệ thống|
:Xác thực Role = Shipper;
if (Hợp lệ?) then (Đúng)
  :Mở giao diện Shipper;
else (Sai)
  :Báo lỗi;
endif
stop
@enduml
```

### 25 & 26. Xem danh sách & Chi tiết đơn hàng được phân công
```plantuml
@startuml
|Shipper|
start
:Truy cập mục "Đơn hàng của tôi";
|Hệ thống|
:Lấy danh sách đơn có shipper_id khớp;
:Hiển thị danh sách;
|Shipper|
:Nhấn vào 1 đơn hàng;
|Hệ thống|
:Hiển thị tên, SĐT, địa chỉ, số tiền COD;
stop
@enduml
```

### 27. Cập nhật trạng thái lấy hàng, kết quả giao hàng
```plantuml
@startuml
|Shipper|
start
:Xem chi tiết đơn hàng;
if (Đã lấy hàng?) then (Đã lấy)
  :Nhấn "Bắt đầu giao";
  |Hệ thống|
  :Cập nhật = "Shipping";
else (Đang giao)
  |Shipper|
  :Đến nhà khách hàng;
  if (Thành công?) then (Khách nhận)
    :Nhấn "Giao thành công";
    |Hệ thống|
    :Cập nhật = "Completed";
  else (Thất bại)
    |Shipper|
    :Nhấn "Giao thất bại";
    |Hệ thống|
    :Cập nhật = "Failed Delivery";
  endif
endif
stop
@enduml
```

### 28. Ghi chú, báo cáo sự cố giao hàng
```plantuml
@startuml
|Shipper|
start
:Đơn hàng giao thất bại;
:Mở popup Ghi chú;
:Nhập lý do (Ví dụ: Khách boom hàng);
:Nhấn "Gửi báo cáo";
|Hệ thống|
:Lưu ghi chú vào CSDL đính kèm đơn hàng;
:Thông báo cho Admin;
stop
@enduml
```

### 29. Xem thống kê lịch sử giao hàng cá nhân
```plantuml
@startuml
|Shipper|
start
:Truy cập mục "Thống kê";
|Hệ thống|
:Tính toán số đơn thành công/thất bại;
:Hiển thị tổng tiền COD đã thu;
|Shipper|
:Xem để đối soát cuối ngày;
stop
@enduml
```

### 30. Cập nhật thông tin tài khoản cá nhân, đổi mật khẩu
```plantuml
@startuml
|Người dùng (All)|
start
:Truy cập "Hồ sơ cá nhân";
:Đổi thông tin hoặc nhập mật khẩu mới;
:Nhấn "Cập nhật";
|Hệ thống|
:Kiểm tra mật khẩu cũ (nếu có);
:Lưu dữ liệu mới;
:Thông báo thành công;
stop
@enduml
```

### 31. Nhận và xem thông báo từ hệ thống
```plantuml
@startuml
|Người dùng (All)|
start
:Nhấn vào icon "Quả chuông" (Notification);
|Hệ thống|
:Lấy danh sách thông báo chưa đọc từ CSDL;
:Hiển thị danh sách;
|Người dùng (All)|
:Click vào 1 thông báo;
|Hệ thống|
:Đánh dấu "Đã đọc";
:Chuyển hướng đến link chi tiết (Ví dụ: Đơn hàng);
stop
@enduml
```

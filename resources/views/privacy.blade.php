@extends('layouts.app')

@section('title', 'Chính Sách Bảo Mật | Suit Configurator')
@section('description', 'Chính sách bảo mật của Suit Configurator. Cách chúng tôi thu thập, sử dụng và bảo vệ thông tin cá nhân của bạn.')
@section('keywords', 'chính sách bảo mật, privacy policy, bảo vệ thông tin, suit configurator')

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
  {
      "@@type": "ListItem",
      "position": 1,
      "name": "Trang chủ",
      "item": "{{ route('home') }}"
  },
  {
      "@@type": "ListItem",
      "position": 2,
      "name": "Chính sách bảo mật",
      "item": "{{ route('privacy') }}"
  }
  ]
}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-white">
  <div class="container mx-auto px-4 py-16">
  <div class="max-w-3xl mx-auto">
      <h1 class="text-4xl font-serif font-bold mb-8">Chính sách bảo mật</h1>
      <p class="text-neutral-500 mb-12">Cập nhật lần cuối: {{ date('d/m/Y') }}</p>

      <div class="prose prose-neutral max-w-none">
    <h2>1. Giới thiệu</h2>
    <p>
          Suit Configurator ("chúng tôi") cam kết bảo vệ quyền riêng tư của bạn. Chính sách bảo mật
          này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ thông tin cá nhân của bạn
          khi bạn sử dụng website và dịch vụ của chúng tôi.
    </p>

    <h2>2. Thông tin chúng tôi thu thập</h2>
    <h3>2.1. Thông tin bạn cung cấp</h3>
    <ul>
          <li>Họ tên, số điện thoại, email</li>
          <li>Địa chỉ giao hàng</li>
          <li>Số đo cơ thể để may đo sản phẩm</li>
          <li>Thông tin thanh toán (được xử lý qua cổng thanh toán bảo mật)</li>
          <li>Các thiết kế và cấu hình sản phẩm bạn tạo</li>
    </ul>

    <h3>2.2. Thông tin tự động thu thập</h3>
    <ul>
          <li>Địa chỉ IP và thông tin trình duyệt</li>
          <li>Cookies và dữ liệu phiên truy cập</li>
          <li>Lịch sử duyệt web và tương tác với website</li>
    </ul>

    <h2>3. Mục đích sử dụng thông tin</h2>
    <p>Chúng tôi sử dụng thông tin của bạn để:</p>
    <ul>
          <li>Xử lý đơn hàng và cung cấp dịch vụ may đo</li>
          <li>Liên hệ về đơn hàng, xác nhận số đo, giao hàng</li>
          <li>Gửi thông tin khuyến mãi và cập nhật (nếu bạn đồng ý)</li>
          <li>Cải thiện trải nghiệm người dùng trên website</li>
          <li>Phân tích và nghiên cứu thị trường</li>
          <li>Tuân thủ các yêu cầu pháp lý</li>
    </ul>

    <h2>4. Chia sẻ thông tin</h2>
    <p>Chúng tôi có thể chia sẻ thông tin của bạn với:</p>
    <ul>
          <li><strong>Đối tác giao hàng:</strong> Để giao sản phẩm đến địa chỉ của bạn</li>
          <li><strong>Cổng thanh toán:</strong> Để xử lý thanh toán an toàn</li>
          <li><strong>Nhà cung cấp dịch vụ:</strong> Lưu trữ dữ liệu, email marketing</li>
          <li><strong>Cơ quan pháp luật:</strong> Khi được yêu cầu theo quy định</li>
    </ul>
    <p>Chúng tôi không bán hoặc cho thuê thông tin cá nhân của bạn cho bên thứ ba.</p>

    <h2>5. Bảo mật thông tin</h2>
    <p>Chúng tôi áp dụng các biện pháp bảo mật để bảo vệ thông tin của bạn:</p>
    <ul>
          <li>Mã hóa SSL cho tất cả giao dịch</li>
          <li>Lưu trữ dữ liệu trên máy chủ bảo mật</li>
          <li>Hạn chế quyền truy cập vào thông tin cá nhân</li>
          <li>Đào tạo nhân viên về bảo mật thông tin</li>
    </ul>

    <h2>6. Cookie</h2>
    <p>
          Website sử dụng cookies để cải thiện trải nghiệm của bạn. Cookies giúp chúng tôi:
    </p>
    <ul>
          <li>Ghi nhớ đăng nhập và giỏ hàng của bạn</li>
          <li>Lưu các thiết kế bạn đã tạo</li>
          <li>Phân tích cách bạn sử dụng website</li>
    </ul>
    <p>Bạn có thể tắt cookies trong cài đặt trình duyệt, nhưng một số tính năng có thể không hoạt động.</p>

    <h2>7. Quyền của bạn</h2>
    <p>Theo Nghị định 13/2023/NĐ-CP về bảo vệ dữ liệu cá nhân, bạn có quyền:</p>
    <ul>
          <li>Truy cập và xem thông tin cá nhân của mình</li>
          <li>Yêu cầu chỉnh sửa thông tin không chính xác</li>
          <li>Yêu cầu xóa thông tin cá nhân</li>
          <li>Phản đối việc xử lý thông tin</li>
          <li>Rút lại sự đồng ý đã cung cấp</li>
    </ul>

    <h2>8. Lưu trữ dữ liệu</h2>
    <p>
          Chúng tôi lưu trữ thông tin của bạn trong thời gian cần thiết để cung cấp dịch vụ
          và tuân thủ các yêu cầu pháp lý. Thông tin đơn hàng được lưu trữ tối thiểu 5 năm
          theo quy định thuế.
    </p>

    <h2>9. Trẻ em</h2>
    <p>
          Dịch vụ của chúng tôi không dành cho người dưới 16 tuổi. Chúng tôi không cố ý
          thu thập thông tin của trẻ em.
    </p>

    <h2>10. Thay đổi chính sách</h2>
    <p>
          Chúng tôi có thể cập nhật chính sách bảo mật này. Mọi thay đổi quan trọng
          sẽ được thông báo qua email hoặc trên website.
    </p>

    <h2>11. Liên hệ</h2>
    <p>Nếu có câu hỏi về chính sách bảo mật, vui lòng liên hệ:</p>
    <ul>
          <li>Email: privacy@suitconfigurator.vn</li>
          <li>Hotline: 0901 234 567</li>
          <li>Địa chỉ: 123 Phố Huế, Quận Hai Bà Trưng, Hà Nội</li>
    </ul>
      </div>
  </div>
  </div>
</div>
@endsection

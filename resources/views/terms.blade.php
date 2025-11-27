@extends('layouts.app')

@section('title', 'Điều Khoản Dịch Vụ | Suit Configurator')
@section('description', 'Điều khoản và điều kiện sử dụng dịch vụ may đo vest tại Suit Configurator. Bao gồm quy định về đặt hàng, thanh toán, giao hàng.')
@section('keywords', 'điều khoản suit configurator, quy định may vest, chính sách đặt hàng vest')

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
      "name": "Điều khoản dịch vụ",
      "item": "{{ route('terms') }}"
  }
  ]
}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-white">
  <div class="container mx-auto px-4 py-16">
  <div class="max-w-3xl mx-auto">
      <h1 class="text-4xl font-serif font-bold mb-8">Điều khoản dịch vụ</h1>
      <p class="text-neutral-500 mb-12">Cập nhật lần cuối: {{ date('d/m/Y') }}</p>

      <div class="prose prose-neutral max-w-none">
    <h2>1. Giới thiệu</h2>
    <p>
          Chào mừng bạn đến với Suit Configurator. Bằng việc truy cập và sử dụng website này,
          bạn đồng ý tuân thủ các điều khoản và điều kiện được nêu dưới đây.
    </p>

    <h2>2. Định nghĩa</h2>
    <ul>
          <li><strong>"Chúng tôi", "Công ty":</strong> Suit Configurator và các đối tác liên quan.</li>
          <li><strong>"Bạn", "Khách hàng":</strong> Người sử dụng dịch vụ của chúng tôi.</li>
          <li><strong>"Dịch vụ":</strong> Dịch vụ may đo vest và các sản phẩm liên quan.</li>
          <li><strong>"Website":</strong> Trang web suitconfigurator.vn và các tên miền liên quan.</li>
    </ul>

    <h2>3. Sử dụng dịch vụ</h2>
    <h3>3.1. Đặt hàng</h3>
    <p>
          Khi đặt hàng trên website, bạn cam kết cung cấp thông tin chính xác về số đo cơ thể,
          thông tin liên hệ và địa chỉ giao hàng. Chúng tôi không chịu trách nhiệm nếu sản phẩm
          không vừa do bạn cung cấp số đo sai.
    </p>

    <h3>3.2. Thanh toán</h3>
    <p>
          Đơn hàng chỉ được xác nhận và đưa vào sản xuất sau khi chúng tôi nhận được thanh toán
          đặt cọc (nếu có) hoặc thanh toán toàn bộ. Giá sản phẩm được hiển thị đã bao gồm VAT.
    </p>

    <h3>3.3. Sản xuất và giao hàng</h3>
    <p>
          Thời gian sản xuất thông thường là 14-21 ngày làm việc kể từ ngày xác nhận đơn hàng.
          Thời gian có thể thay đổi tùy theo độ phức tạp của sản phẩm và số lượng đơn hàng.
    </p>

    <h2>4. Chính sách hoàn trả</h2>
    <p>
          Do tính chất may đo riêng của sản phẩm, chúng tôi không chấp nhận đổi trả ngoại trừ
          các trường hợp sau:
    </p>
    <ul>
          <li>Sản phẩm có lỗi kỹ thuật từ nhà sản xuất</li>
          <li>Sản phẩm không đúng với thiết kế đã xác nhận</li>
          <li>Sản phẩm bị hư hỏng trong quá trình vận chuyển</li>
    </ul>

    <h2>5. Bảo hành</h2>
    <p>
          Tất cả sản phẩm đều được bảo hành trọn đời theo chính sách bảo hành của Suit Configurator.
          Chi tiết xem tại <a href="{{ route('warranty') }}">trang bảo hành</a>.
    </p>

    <h2>6. Quyền sở hữu trí tuệ</h2>
    <p>
          Mọi nội dung trên website bao gồm nhưng không giới hạn: hình ảnh, văn bản, logo,
          thiết kế đều thuộc quyền sở hữu của Suit Configurator. Nghiêm cấm sao chép,
          sử dụng mà không có sự đồng ý bằng văn bản.
    </p>

    <h2>7. Giới hạn trách nhiệm</h2>
    <p>
          Suit Configurator không chịu trách nhiệm đối với bất kỳ thiệt hại gián tiếp,
          đặc biệt hoặc do hậu quả phát sinh từ việc sử dụng dịch vụ của chúng tôi.
    </p>

    <h2>8. Thay đổi điều khoản</h2>
    <p>
          Chúng tôi có quyền cập nhật các điều khoản này bất cứ lúc nào. Mọi thay đổi
          sẽ có hiệu lực ngay khi được đăng tải trên website.
    </p>

    <h2>9. Liên hệ</h2>
    <p>
          Nếu có bất kỳ câu hỏi nào về điều khoản dịch vụ, vui lòng liên hệ:
    </p>
    <ul>
          <li>Email: legal@suitconfigurator.vn</li>
          <li>Hotline: 0901 234 567</li>
          <li>Địa chỉ: 123 Phố Huế, Quận Hai Bà Trưng, Hà Nội</li>
    </ul>
      </div>
  </div>
  </div>
</div>
@endsection

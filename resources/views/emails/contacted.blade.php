以下のお客様よりお問い合わせがありました。<br>
お手数ではございますが、できるだけ早い対応をお願いいたします。<br><br>

お名前：{{ $name }}<br>
@if(!empty($customer_id))
顧客ID：{{ $customer_id }}<br>
@endif
メールアドレス：{{ $email }}<br>
問い合わせ区分：{{ $contact_subject_type }}<br>
@if(!empty($order_id))
注文ID：{{ $order_id }}<br>
@endif
件名：{{ $subject }}<br>
お問い合わせ内容：<br>
{!! nl2br($body) !!}

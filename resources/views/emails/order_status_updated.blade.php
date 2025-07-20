注文番号「<a href="{{ route('admin.order.index', ['order_unique_id' => $order->order_unique_id]) }}">{{ $order->order_unique_id }}</a>」のステータスが<br><br>

{{ $history->status_from_label }}  → <b>{{ $history->status_to_label }}</b><br><br>

へ更新されました。<br><br>

@if(! is_null($multi_auth_user))
変更した人： {{ $multi_auth_user->user->name }}（{{ $multi_auth_user->user_type_label }}）
@else
ログインユーザーなし
@endif

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('mobile.user.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

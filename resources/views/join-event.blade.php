<!-- resources/views/events/join.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Join Event</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        input { padding: 8px; width: 100%; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Join Event: {{ $event->title }}</h2>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ url('/join-event/' . $event->id) }}">
        @csrf

        <label for="name">Your Name:</label>
        <input type="text" name="name" required>

        <label for="phone">Phone Number:</label>
        <input type="number" name="phone" required>

        <button type="submit">Join Event</button>
    </form>
</body>
</html>

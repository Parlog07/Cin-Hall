<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-lg">
        
        <h1 class="text-2xl font-bold mb-4 text-center">🎟️ Movie Ticket</h1>

        <!-- Film Info -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold border-b pb-2 mb-2">Film</h2>
            <p><strong>Title:</strong> {{ $film['title'] }}</p>
            <p><strong>Description:</strong> {{ $film['description'] }}</p>
            <p><strong>Genre:</strong> {{ $film['genre'] }}</p>
            <p><strong>Actors:</strong> {{ $film['actors'] }}</p>
            <p><strong>Duration:</strong> {{ $film['duration_minutes'] }} min</p>
            <p><strong>Minimum Age:</strong> {{ $film['minimum_age'] }}+</p>
            <p><strong>Trailer:</strong> {{ $film['trailer_url'] }}</p>
        </div>

        <!-- Session Info -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold border-b pb-2 mb-2">Session</h2>
            <p><strong>Language:</strong> {{ $session['language'] }}</p>
            <p><strong>Type:</strong> {{ $session['type'] }}</p>
            <p><strong>Price:</strong> {{ $session['price'] }}</p>
            <p><strong>Start Time:</strong> {{ $session['start_time'] }}</p>
        </div>

        <!-- Room Info -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold border-b pb-2 mb-2">Room</h2>
            <p><strong>Name:</strong> {{ $room['name'] }}</p>
            <p><strong>Type:</strong> {{ $room['type'] }}</p>
            <p><strong>Capacity:</strong> {{ $room['capacity'] }}</p>
        </div>

        <!-- Seat Info -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold border-b pb-2 mb-2">Seat</h2>
            <p><strong>Number:</strong> {{ $seat['number'] }}</p>
            <p><strong>Type:</strong> {{ $seat['type'] }}</p>
            <p><strong>Room ID:</strong> {{ $seat['room_id'] }}</p>
        </div>

        <!-- Reservation Info -->
        <div>
            <h2 class="text-xl font-semibold border-b pb-2 mb-2">Reservation</h2>
            <p><strong>Status:</strong> {{ $reservation['status'] }}</p>
            <p><strong>Expires At:</strong> {{ $reservation['expires_at'] }}</p>
            <p><strong>Total Price:</strong> {{ $reservation['total_price'] }}</p>
            <p><strong>Room Session ID:</strong> {{ $reservation['room_session_id'] }}</p>
            <p><strong>User ID:</strong> {{ $reservation['user_id'] }}</p>
        </div>

        <div class="mt-6 text-center text-sm text-gray-500">
            Generated at {{ now() }}
        </div>

    </div>
</body>
</html>
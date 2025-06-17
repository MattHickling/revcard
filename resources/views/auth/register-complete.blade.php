<style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f9fafb;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 2rem auto;
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    h2 {
        margin-bottom: 1.5rem;
        text-align: center;
        color: #222;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #4f46e5;
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .mb-3 {
        margin-bottom: 1.25rem;
    }

    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .btn:hover {
        background-color: #4338ca;
    }

    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.25rem;
        font-size: 0.95rem;
    }

    .alert-danger {
        background-color: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1.2rem;
    }

    .alert-danger li {
        list-style: disc;
    }
    .revcard-title {
        text-align: center;
        font-size: 2.75rem;
        font-weight: 800;
        color: #4f46e5;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

</style>


<div class="container" style="max-width: 600px; margin: 2rem auto;">
    <h1 class="revcard-title">RevCard</h1>
    <h2>Complete Your Registration</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.invited.post', $invite->token) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input id="email" type="email" class="form-control" value="{{ $invite->email }}" disabled>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input id="password" name="password" type="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Complete Registration</button>
    </form>
</div>

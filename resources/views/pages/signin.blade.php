@extends('app')

@section('title', 'Sign In - Tulip Store')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold text-primary mb-2">Tulip Store</h1>
      <p class="text-gray-600">Sign in to your account</p>
    </div>

    <!-- Sign In Form -->
    <form id="signin-form" class="space-y-6">
      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          required
          placeholder="you@example.com"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        >
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          required
          placeholder="••••••••"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        >
      </div>

      <!-- Remember Me -->
      <div class="flex items-center">
        <input 
          type="checkbox" 
          id="remember" 
          name="remember"
          class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
        >
        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
      </div>

      <!-- Sign In Button -->
      <button 
        type="submit" 
        class="w-full btn-primary py-3 text-lg font-semibold"
      >
        Sign In
      </button>

      <!-- Error Message -->
      <div id="error-message" class="hidden p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"></div>
    </form>

    <!-- Divider -->
    <div class="my-8 flex items-center gap-4">
      <div class="flex-1 border-t border-gray-300"></div>
      <span class="text-gray-500 text-sm">Or</span>
      <div class="flex-1 border-t border-gray-300"></div>
    </div>

    <!-- Google Sign In -->
    <button id="google-signin" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition font-semibold text-gray-900 flex items-center justify-center gap-2">
      <i class="fab fa-google text-red-500"></i> Sign in with Google
    </button>

    <!-- Sign Up Link -->
    <p class="text-center text-gray-600 mt-8">
      Don't have an account? 
      <a href="/signup" class="text-primary hover:text-pink-600 font-semibold transition">Sign up</a>
    </p>

    <!-- Forgot Password Link -->
    <p class="text-center mt-4">
      <a href="/forgot-password" class="text-sm text-primary hover:text-pink-600 transition">Forgot password?</a>
    </p>
  </div>
</div>

<script>
  document.getElementById('signin-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorMsg = document.getElementById('error-message');

    try {
      errorMsg.classList.add('hidden');
      
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
      });

      const data = await response.json();

      if (!response.ok) {
        errorMsg.textContent = data.message || 'Login failed. Please try again.';
        errorMsg.classList.remove('hidden');
        return;
      }

      // Store token and redirect
      localStorage.setItem('auth_token', data.token);
      localStorage.setItem('user', JSON.stringify(data.user));
      window.location.href = '/';

    } catch (error) {
      console.error('Sign in error:', error);
      errorMsg.textContent = 'An error occurred. Please try again.';
      errorMsg.classList.remove('hidden');
    }
  });

  // Google Sign In (setup)
  document.getElementById('google-signin').addEventListener('click', () => {
    console.log('Google sign in button clicked');
    // Initialize Google Sign-In if configured
    if (window.google && window.google.accounts) {
      window.google.accounts.id.initialize({
        client_id: 'YOUR_GOOGLE_CLIENT_ID',
        callback: handleGoogleSignIn
      });
      window.google.accounts.id.renderButton(
        document.getElementById('google-signin'),
        { theme: 'outline', size: 'large' }
      );
    }
  });

  function handleGoogleSignIn(response) {
    // Send token to backend
    console.log('Google Sign-In response:', response);
    // Implement backend authentication with Google token
  }
</script>
@endsection

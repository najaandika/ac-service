@extends('layouts.guest')

@section('title', 'Login - AC Service')
@section('description', 'Login to AC Service admin dashboard.')

@section('content')
<!-- Login Card -->
<div class="bg-white rounded-[var(--radius-card)] p-8 shadow-xl shadow-gray-200/50">
    <h2 class="text-foreground text-xl font-bold mb-2">Selamat Datang!</h2>
    <p class="text-gray-500 text-sm mb-6">Silakan login untuk melanjutkan</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <x-forms.input 
            type="email"
            name="email"
            label="Email"
            icon="mail"
            placeholder="admin@acservice.com"
            :required="true"
            autocomplete="email"
        />

        <div class="mb-4">
            <label for="password" class="block text-foreground text-sm font-medium mb-2">
                Password<span class="text-error">*</span>
            </label>
            <div class="relative">
                <i data-lucide="lock" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    class="form-input form-input-icon pr-12"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
                <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer">
                    <i data-lucide="eye" id="eye-icon" class="w-5 h-5 text-gray-400 hover:text-primary transition-colors"></i>
                </button>
            </div>
            @error('password')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 text-primary border-border rounded focus:ring-primary">
                <span class="text-gray-600 text-sm">Ingat saya</span>
            </label>
            <a href="#" class="text-primary text-sm font-medium hover:underline">Lupa password?</a>
        </div>

        <!-- Login Button -->
        <x-button type="submit" variant="primary" class="w-full justify-center shadow-lg shadow-primary/30">
            Login
        </x-button>
    </form>
</div>
@endsection

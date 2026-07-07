@extends('layouts.app')

@section('title', 'Login Administrator - Kalkulator Mahasiswa')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-6 px-4">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 items-center justify-center text-white text-4xl shadow-lg shadow-indigo-200 mb-4">
                🔐
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Admin Login</h2>
            <p class="text-slate-500 font-medium mt-1">Masuk untuk mengelola data biaya & beasiswa</p>
        </div>

        <!-- Glassmorphism Login Card -->
        <div class="glassmorphism p-8 rounded-3xl border border-slate-200/80 shadow-xl bg-white/80">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Admin</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm">
                            📧
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 placeholder-slate-400 font-medium text-sm transition-all outline-none"
                            placeholder="nama@kalkulator.com">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm">
                            🔑
                        </span>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 placeholder-slate-400 font-medium text-sm transition-all outline-none"
                            placeholder="Masukkan password Anda">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" 
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                    <label for="remember" class="ml-2.5 block text-xs font-semibold text-slate-600 select-none cursor-pointer">
                        Ingat Saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 shadow-md shadow-indigo-100 hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
                    &larr; Kembali ke Kalkulator Publik
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

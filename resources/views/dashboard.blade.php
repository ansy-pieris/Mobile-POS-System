<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-blue-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800">Total Invoices</h3>
                        <p class="text-3xl font-bold text-blue-900">{{ \App\Models\Invoice::count() }}</p>
                    </div>
                    <div class="bg-green-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800">Total Products</h3>
                        <p class="text-3xl font-bold text-green-900">{{ \App\Models\Product::count() }}</p>
                    </div>
                    <div class="bg-purple-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-purple-800">Total Customers</h3>
                        <p class="text-3xl font-bold text-purple-900">{{ \App\Models\Customer::count() }}</p>
                    </div>
                    <div class="bg-orange-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-orange-800">Total Sales</h3>
                        <p class="text-3xl font-bold text-orange-900">Rs.{{ number_format(\App\Models\Invoice::sum('total_amount'), 2) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('invoice.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-200">
                        Create New Invoice
                    </a>
                    <a href="{{ route('products') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-200">
                        Manage Products
                    </a>
                    <a href="{{ route('customers') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-200">
                        Manage Customers
                    </a>
                    <a href="{{ route('categories') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-200">
                        Manage Categories
                    </a>
                    <a href="{{ route('invoices.list') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-200">
                        View Invoices
                    </a>
                    <a href="{{ route('settings') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-200">
                        Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

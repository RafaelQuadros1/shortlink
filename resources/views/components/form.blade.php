<div class="w-full flex flex-col">
    <form action="{{ route('shorts.store') }}" method="POST" class="w-full flex flex-col gap-4">
        @csrf
        <input type="text" name="url_origin" placeholder="Enter your URL here"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit"
            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">Shorten</button>
    </form>

</div>

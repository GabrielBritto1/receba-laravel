@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    {{ session('success') }}
</div>
@endif

@if(session('message'))
<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
    {{ session('message') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    {{ session('error') }}
</div>
@endif

@if($errors->any())
<ul>
    @foreach($errors->all() as $error)
    <li class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ $error }}</li>
    @endforeach
</ul>
@endif
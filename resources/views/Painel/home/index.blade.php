@extends('painel.layouts.painel')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
        	<a href="/painel/users" title="Users"><h2>Total Users {{ $totalUsers ?? 'NULL' }}</h2></a>
        </div>
        <div class="col-md-3">
        	<a href="/painel/roles" title="Roles"><h2>Total Roles {{ $totalRoles ?? 'NULL' }}</h2></a>
        </div>
        <div class="col-md-3">
        	<a href="/painel/permissions" title="Permissions"><h2>Total Permissions {{ $totalPermissions ?? 'NULL' }}</h2></a>
        </div>
        <div class="col-md-3">
        	<a href="/painel/posts" title="Posts"><h2>Total Posts {{ $totalPosts ?? 'NULL' }}</h2></a>
        </div>
    </div>
</div>
<div class="footer">
    {{-- <p>IP Real : {{  $IpReal }} - Usuario : {{ username(Auth::id()) }}</p>
    <p>Date: {{ dateBR('2017/11/08') }} - Get Slug: {{ getSlug('Produções Atívos') }}</p>
    <p>Helper Class: {{ Helper::shout('My Love') }}</p>
    {!! Helper::shout('this is how to use autoloading correctly!!') !!} <br>
    {!! MyFuncs::full_name("Eber Ortiz", "Mas") !!} <br>
    {{ MyFuncs::makeSlug("Produções Atívos") }}<hr>
    {{ MyFuncs::makeOrdinal(9) }} --}}
</div>
@endsection
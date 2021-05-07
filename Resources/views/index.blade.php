@extends('backend.layouts.app')

@section('title')Menu Builder @stop

@section('breadcrumbs')
    <x-backend-breadcrumbs>
        <x-backend-breadcrumb-item type="active" icon='fas fa-sitemap'>MenuMaker</x-backend-breadcrumb-item>
    </x-backend-breadcrumbs>
@stop

@section('content')

    <?php
    $currentUrl = url()->current();
    ?>

    {!! (new Modules\Menumaker\Http\Controllers\Backend\MenuBuilderController)->render() !!}

@endsection

@push ('after-styles')
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="{{asset('vendor/simplepleb/menumaker/style.css')}}" rel="stylesheet">
@endpush

@push ('after-scripts')

    <script>
        var menus = {
            "oneThemeLocationNoMenus" : "",
            "moveUp" : "Move up",
            "moveDown" : "Mover down",
            "moveToTop" : "Move top",
            "moveUnder" : "Move under of %s",
            "moveOutFrom" : "Out from under  %s",
            "under" : "Under %s",
            "outFrom" : "Out from %s",
            "menuFocus" : "%1$s. Element menu %2$d of %3$d.",
            "subMenuFocus" : "%1$s. Menu of subelement %2$d of %3$s."
        };
        var arraydata = [];
        var addcustommenur          = '{{ route("backend.addcustommenu") }}';
        var updateitemr             = '{{ route("backend.updateitem")}}';
        var generatemenucontrolr    = '{{ route("backend.generatemenucontrol") }}';
        var deleteitemmenur         = '{{ route("backend.deleteitemmenu") }}';
        var deletemenugr            = '{{ route("backend.deletemenug") }}';
        var createnewmenur          = '{{ route("backend.createnewmenu") }}';
        var csrftoken               ="{{ csrf_token() }}";
        var menuwr                  = "{{ url()->current() }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrftoken
            }
        });
    </script>
    <script type="text/javascript" src="{{asset('vendor/simplepleb/menumaker/scripts.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/simplepleb/menumaker/scripts2.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/simplepleb/menumaker/menu.js')}}?id={{ time() }}"></script>
@endpush

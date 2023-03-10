<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@if(!isset($hide_breadcrumb))
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ $row_title }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render($route_name) }}
        </div>
    </div>
@endif

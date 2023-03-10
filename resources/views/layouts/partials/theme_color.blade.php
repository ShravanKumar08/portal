<ul id="themecolors" style="padding-left: 0px;">
    <li><b>With Light sidebar</b></li>
    @php
        $themes = [
            'default',
            'green',
            'red',
            'blue',
            'purple',
            'megna',
        ];
    @endphp

    @foreach($themes as $theme)
        <li><a href="javascript:void(0)" data-theme="{{ $theme }}" class="{{ $theme }}-theme {{ $Model->value == $theme ? 'working' : '' }}">1</a></li>
    @endforeach
    <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
    @foreach($themes as $theme)
        <li><a href="javascript:void(0)" data-theme="{{ $theme }}-dark" class="{{ $theme }}-dark-theme {{ $Model->value == $theme.'-dark' ? 'working' : '' }}">1</a></li>
    @endforeach
</ul>

{{ Form::hidden('value') }}

@push('scripts')
    <script type="text/javascript">
        // Theme color settings
        var base_url = '{!! json_encode(url('/')) !!}';
        $(document).ready(function() {
            function store(name, val) {
                if (typeof(Storage) !== "undefined") {
                    localStorage.setItem(name, val);
                } else {
                    window.alert('Please use a modern browser to properly view this template!');
                }
            }

            $("*[data-theme]").click(function(e) {
                e.preventDefault();
                var currentStyle = $(this).attr('data-theme');
                /*store('theme', currentStyle);*/
                $('#theme').attr({ href: "../../../css/colors/" + currentStyle + ".css" });
                $('input[name="value"]').val(currentStyle);
            });

            var currentTheme = localStorage.getItem("theme");
            if (currentTheme) {
                $('#theme').attr({ href: '../../../css/colors/' + currentTheme + '.css' });
            }
            // color selector
            $('#themecolors').on('click', 'a', function() {
                $('#themecolors li a').removeClass('working');
                $(this).addClass('working')
            });

        });
    </script>
@endpush
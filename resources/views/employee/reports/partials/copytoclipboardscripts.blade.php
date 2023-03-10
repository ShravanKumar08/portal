@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
       $('body').on('click', '#copy-button-clone', function () {
           $('#DatatableViewModal').modal('hide');
           $('#copy-button')[0].click();
       })
    });
    var clipboard = new Clipboard('#copy-button', {
        text: function () {
            var reports = {};
            $('#items tbody tr').each(function () {
                var proj = $(this).find('td:nth(1)').html();
                var work = $(this).find('td:nth(3)').html(); //+ ' - ' + $(this).find('td:nth(6)').html();
                if (proj != '-') {
                    if (typeof reports[proj] == 'undefined') {
                        reports[proj] = [];
                    }
                    if ($.inArray(work, reports[proj]) == -1)
                        reports[proj].push(work);
                }
            });

            var html = "Work Status for the Day: \n";
            $.each(reports, function (k, v) {
                html += k + ':' + '\n';
                $.each(v, function (k2, v2) {
                    var rk = k2 + 1;
                    html += rk + ') ' + v2 + '\n';
                });
                html += '\n';
            });

            html = html.slice(0, -2);
            return html;
        }
    });

    clipboard.on('success', function (e) {
        swal({
            title: "Report copied!",
            type: "success",
        });
    });

</script>
@endpush

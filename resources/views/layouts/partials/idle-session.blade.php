<div id="idle-timeout-dialog" data-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Your session is expiring soon</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>
                    <i class="fa fa-warning font-red"></i> You session will be locked in
                    <span id="idle-timeout-counter"></span> seconds.</p>
                <p> Do you want to continue your session? </p>
            </div>
            <div class="modal-footer text-center">
                <button id="idle-timeout-dialog-keepalive" type="button" class="btn btn-success" data-dismiss="modal">Yes, Keep Working</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        //Session time out
        $(document).ready(function () {
            UIIdleTimeout.init();
        });

        var UIIdleTimeout = function() {
            return {
                init: function() {
                    var UIdle;
                    $("body").append(""), $.idleTimeout("#idle-timeout-dialog", ".modal-content button:last", {
                        idleAfter: {{ config('session.lifetime') * 60 }},
                        timeout: 30,
                        pollingInterval: 30,
                        keepAliveURL: "{{ route('keep-alive') }}",
                        serverResponseEquals: "OK",
                        onTimeout: function() {
                            window.location = "{{ route('login') }}"
                        },
                        onIdle: function() {
                            $("#idle-timeout-dialog").modal("show"), UIdle = $("#idle-timeout-counter"), $("#idle-timeout-dialog-keepalive").on("click", function() {
                                $("#idle-timeout-dialog").modal("hide")
                            })
                        },
                        onCountdown: function(e) {
                            UIdle.html(e)
                        }
                    })
                }
            }
        }();
    </script>
@endpush
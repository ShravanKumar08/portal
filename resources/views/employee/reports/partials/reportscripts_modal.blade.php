<div class="modal fade bs-example-modal-lg" id="releaseLockModal" role="dialog" aria-labelledby="releaseLockModal">
    <div class="modal-dialog modal-md" role="document" style="padding-top: 60px">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Release Lock (Break Hours) Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                @include('layouts.partials.loader-content')
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="githubModal" role="dialog" aria-labelledby="releaseLockModal">
    <div class="modal-dialog modal-xl" role="document" style="padding-top: 60px">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Github Latest Commits  <button type='button' class="btn-warning github-refresh mdi mdi-refresh"> Refresh</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                @include('layouts.partials.loader-content')
            </div>
        </div>
    </div>
</div>
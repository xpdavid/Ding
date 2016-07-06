{{--// crop image model--}}
<div class="modal fade" id="crop_img_{{ $id }}_modal" tabindex="-1" role="dialog" aria-labelledby="crop_image">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crop Image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="crop_img_bound center-block">
                            <img src="{{ $image }}" id="crop_img_{{ $id }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center margin-top">
                        <div class="btn-group" role="group">
                            <button type="button" id="crop_img_{{ $id }}_left_rotate" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Rotate Left">
                                    <span>
                                      <span class="glyphicon glyphicon-chevron-left clear_margin"></span>
                                    </span>
                            </button>
                            <button type="button" id="crop_img_{{ $id }}_right_rotate" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Rotate Right">
                                    <span>
                                      <span class="glyphicon glyphicon-chevron-right clear_margin"></span>
                                    </span>
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" id="crop_img_{{ $id }}_reset" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Reset">
                                    <span>
                                      <span class="glyphicon glyphicon-refresh clear_margin"></span>
                                    </span>
                            </button>
                            <label class="btn btn-primary btn-upload" data-toggle="tooltip" data-placement="bottom" title="Upload File">
                                <input type="file" class="sr-only" id="crop_img_{{ $id }}_upload" name="file" accept="image/*">
                                    <span>
                                      <span class="glyphicon glyphicon-folder-open clear_margin"></span>
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="crop_img_{{ $id }}_upload_click"
                        data-url="{{ $url }}" data-id="{{ $id }}" data-type="{{ $type }}"
                >Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
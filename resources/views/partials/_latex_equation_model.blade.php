{{--// latex_equation model--}}
<div class="modal fade" id="latex_equation" tabindex="-1" role="dialog" aria-labelledby="latex_equation">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Input Your Equation</h4>
            </div>
            <div class="modal-body">
                <div class="noneDisplay">
                    <table class="math-table" id="math-equation-1">
                        <tbody class="math-table-tbody">

                        </tbody>
                    </table>

                    <table class="math-table" id="math-equation-2">
                        <tbody class="math-table-tbody">

                        </tbody>
                    </table>

                    <table class="math-table" id="math-equation-3">
                        <tbody class="math-table-tbody">

                        </tbody>
                    </table>

                    <table class="math-table" id="math-equation-4">
                        <tbody class="math-table-tbody">

                        </tbody>
                    </table>

                    <table class="math-table" id="math-equation-5">
                        <tbody class="math-table-tbody">

                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label>Preview</label>
                    <div id="tex-preview">

                    </div>
                </div>

                <div class="form-group">
                    <label for="Tex-formular">Tex Formular Here</label>
                    <textarea rows="3" class="form-control" id="tex-formula" placeholder="Input your Tex here"></textarea>
                </div>

                <div class="well well-sm">
                    <table class="math-table math-equation-menu" id="math-equation-menu">
                        <tbody class="math-table-tbody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="insertMathFormula()">Insert</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="row" id="megaoptim-compress-files">
	<div class="col-sm-6">
		<div id="megaoptim-compress-params">
			<div class="row">
				<div class="col-sm-6">
					<div class="mc-form-row">
						<label for="compression">Level</label>
						<select name="compression" id="compression" class="form-control">
							<option value="lossless">Lossless</option>
							<option selected value="intelligent">Intelligent</option>
							<option value="ultra">Ultra</option>
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mc-form-row">
						<label for="keep_exif">Keep EXIF data</label>
						<select name="keep_exif" id="keep_exif" class="form-control">
							<option value="1">Yes</option>
							<option selected value="0">No</option>
						</select>
					</div>
				</div>
			</div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="mc-form-row">
                        <label for="webp">Create WebP</label>
                        <select name="webp" id="webp" class="form-control">
                            <option value="1">Yes</option>
                            <option selected value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mc-form-row">
                        <label for="max_width">Resize to max width in px <small>(0 to disable)</small></label>
                        <input type="text" class="form-control px" name="max_width" id="max_width" value="0">
                   
                    </div>
                </div>
            </div>
			<div class="row">
				<div class="col-sm-6">
					<button id="runOptimizer" class="btn btn-primary">Run Optimizer</button>
				</div>
			</div>
		</div>

	</div>
	<div class="col-sm-6">
		<form id="megaoptimCompress" class="dropzone" method="post" enctype="multipart/form-data">
			<div class="dz-message text-center" data-dz-message><p><i class="fa fa-upload"></i> </p><span>Drop images to optimize</span></div>
		</form>
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table id="megaoptim-compress-infotable" style="display: none;" class="table table-bordered">
				<thead>
				<tr>
					<th>Filename</th>
					<th>Status</th>
					<th>Size Before</th>
					<th>Size After</th>
					<th>Total Saved</th>
					<th>Download</th>
				</tr>
				</thead>
                <tbody>

                </tbody>
			</table>
		</div>
	</div>
</div>
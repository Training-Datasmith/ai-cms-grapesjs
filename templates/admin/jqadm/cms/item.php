<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 */
$selected = function ($key, $code) {
    return $key == $code ? 'selected="selected"' : '';
};
$enc = $this->encoder();
$target = $this->config('admin/jqadm/url/save/target');
$cntl = $this->config('admin/jqadm/url/save/controller', 'Jqadm');
$action = $this->config('admin/jqadm/url/save/action', 'save');
$config = $this->config('admin/jqadm/url/save/config', []);
$get_target = $this->config('admin/jqadm/url/get/target');
$get_cntl = $this->config('admin/jqadm/url/get/controller', 'Jqadm');
$get_action = $this->config('admin/jqadm/url/get/action', 'get');
$get_config = $this->config('admin/jqadm/url/get/config', []);
$list_target = $this->config('admin/jqadm/url/search/target');
$list_cntl = $this->config('admin/jqadm/url/search/controller', 'Jqadm');
$list_action = $this->config('admin/jqadm/url/search/action', 'search');
$list_config = $this->config('admin/jqadm/url/search/config', []);
$new_target = $this->config('admin/jqadm/url/create/target');
$new_cntl = $this->config('admin/jqadm/url/create/controller', 'Jqadm');
$new_action = $this->config('admin/jqadm/url/create/action', 'create');
$new_config = $this->config('admin/jqadm/url/create/config', []);
$json_target = $this->config('admin/jsonadm/url/target');
$json_cntl = $this->config('admin/jsonadm/url/controller', 'Jsonadm');
$json_action = $this->config('admin/jsonadm/url/action', 'get');
$json_config = $this->config('admin/jsonadm/url/config', []);
$params = $this->get('pageParams', []);
$this->block()->start('jqadm_content');
?>

<form class="item item-cms item-tree form-horizontal container-fluid" method="POST" enctype="multipart/form-data"
	action="<?php 
echo $enc->attr($this->url($target, $cntl, $action, $params, [], $config));
?>"
	data-rootid="<?php 
echo $enc->attr($this->get('itemRootId'));
?>"
	data-geturl="<?php 
echo $enc->attr($this->url($get_target, $get_cntl, $get_action, ['resource' => 'cms', 'id' => '_ID_'] + $params, [], $get_config));
?>"
	data-createurl="<?php 
echo $enc->attr($this->url($new_target, $new_cntl, $new_action, ['resource' => 'cms', 'id' => '_ID_'] + $params, [], $new_config));
?>"
	data-jsonurl="<?php 
echo $enc->attr($this->url($json_target, $json_cntl, $json_action, ['resource' => 'cms'], [], $json_config));
?>"
	data-idname="<?php 
echo $this->formparam('id');
?>" >

	<input id="item-id" type="hidden" name="<?php 
echo $enc->attr($this->formparam(['item', 'cms.id']));
?>"
		value="<?php 
echo $enc->attr($this->get('itemData/cms.id'));
?>">
	<input id="item-parentid" type="hidden" name="<?php 
echo $enc->attr($this->formparam(['item', 'cms.parentid']));
?>"
		value="<?php 
echo $enc->attr($this->get('itemData/cms.parentid', $this->param('parentid', $this->param('id', $this->get('itemRootId')))));
?>">
	<input id="item-next" type="hidden" name="<?php 
echo $enc->attr($this->formparam(['next']));
?>" value="get">
	<?php 
echo $this->csrf()->formfield();
?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?php 
echo $enc->html($this->translate('admin', 'CMS page'));
?></span>
			<span class="navbar-id"><?php 
echo $enc->html($this->get('itemData/cms.id'));
?></span>
			<span class="navbar-label"><?php 
echo $enc->html($this->get('itemData/cms.label') ?: $this->translate('admin', 'New'));
?></span>
			<span class="navbar-site"><?php 
echo $enc->html($this->site()->match($this->get('itemData/cms.siteid')));
?></span>
		</h1>
		<div class="item-actions">
			<?php 
if (isset($this->item_data)) {
    ?>
				<?php 
    echo $this->partial($this->config('admin/jqadm/partial/itemactions', 'itemactions'), ['params' => $params]);
    ?>
			<?php 
} else {
    ?>
				<span class="placeholder">&nbsp;</span>
			<?php 
}
?>
		</div>
	</nav>

	<div class="row item-container">

		<?php 
if (isset($this->item_data)) {
    ?>
			<div class="col-xl-12 cms-content">
				<div class="row">

					<div class="col-xl-12 item-navbar">
						<div class="navbar-content" v-bind:class="{show: show}">
							<ul class="nav nav-tabs flex-row flex-wrap d-flex box" role="tablist">
								<li class="nav-item basic">
									<a class="nav-link active" href="#basic" data-bs-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic" tabindex="1">
										<?php 
    echo $enc->html($this->translate('admin', 'Basic'));
    ?>
									</a>
								</li>

								<?php 
    foreach (array_values($this->get('itemSubparts', [])) as $idx => $subpart) {
        ?>
									<li class="nav-item <?php 
        echo $enc->attr($subpart);
        ?>">
										<a class="nav-link" href="#<?php 
        echo $enc->attr($subpart);
        ?>" data-bs-toggle="tab" role="tab" tabindex="<?php 
        echo ++$idx + 1;
        ?>">
											<?php 
        echo $enc->html($this->translate('admin', $subpart));
        ?>
										</a>
									</li>
								<?php 
    }
    ?>
							</ul>

							<div class="item-meta text-muted">
								<small>
									<?php 
    echo $enc->html($this->translate('admin', 'Modified'));
    ?>:
									<span class="meta-value"><?php 
    echo $enc->html($this->get('itemData/cms.mtime'));
    ?></span>
								</small>
								<small>
									<?php 
    echo $enc->html($this->translate('admin', 'Created'));
    ?>:
									<span class="meta-value"><?php 
    echo $enc->html($this->get('itemData/cms.ctime'));
    ?></span>
								</small>
								<small>
									<?php 
    echo $enc->html($this->translate('admin', 'Editor'));
    ?>:
									<span class="meta-value"><?php 
    echo $enc->html($this->get('itemData/cms.editor'));
    ?></span>
								</small>
							</div>

							<div class="more"></div>
						</div>
					</div>

					<div class="col-xl-12 item-content tab-content">

						<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

							<div class="vue box <?php 
    echo $this->site()->readonly($this->get('itemData/cms.siteid'));
    ?>"
								data-data="<?php 
    echo $enc->attr($this->get('itemData'));
    ?>"
								data-siteid="<?php 
    echo $enc->attr($this->site()->siteid());
    ?>"
								data-domain="cms">

								<div class="row">
									<div class="col-xl-6">
										<div class="form-group row mandatory">
											<label class="col-sm-4 form-control-label"><?php 
    echo $enc->html($this->translate('admin', 'Status'));
    ?></label>
											<div class="col-sm-8">
												<select class="form-select item-status" required="required" tabindex="1"
													name="<?php 
    echo $enc->attr($this->formparam(['item', 'cms.status']));
    ?>"
													v-bind:readonly="!can('change')" >
													<option value="">
														<?php 
    echo $enc->html($this->translate('admin', 'Please select'));
    ?>
													</option>
													<option value="1" <?php 
    echo $selected($this->get('itemData/cms.status', 1), 1);
    ?> >
														<?php 
    echo $enc->html($this->translate('mshop/code', 'status:1'));
    ?>
													</option>
													<option value="0" <?php 
    echo $selected($this->get('itemData/cms.status', 1), 0);
    ?> >
														<?php 
    echo $enc->html($this->translate('mshop/code', 'status:0'));
    ?>
													</option>
													<option value="-1" <?php 
    echo $selected($this->get('itemData/cms.status', 1), -1);
    ?> >
														<?php 
    echo $enc->html($this->translate('mshop/code', 'status:-1'));
    ?>
													</option>
													<option value="-2" <?php 
    echo $selected($this->get('itemData/cms.status', 1), -2);
    ?> >
														<?php 
    echo $enc->html($this->translate('mshop/code', 'status:-2'));
    ?>
													</option>
												</select>
											</div>
										</div>
										<div class="form-group row mandatory">
											<label class="col-sm-4 form-control-label help"><?php 
    echo $enc->html($this->translate('admin', 'URL'));
    ?></label>
											<div class="col-sm-8">
												<input class="form-control item-url" type="text" required="required" tabindex="1"
													name="<?php 
    echo $enc->attr($this->formparam(['item', 'cms.url']));
    ?>"
													placeholder="<?php 
    echo $enc->attr($this->translate('admin', 'Unique page URL (required)'));
    ?>"
													value="<?php 
    echo $enc->attr($this->get('itemData/cms.url'));
    ?>"
													v-bind:readonly="!can('change')">
											</div>
											<div class="col-sm-12 form-text text-muted help-text">
												<?php 
    echo $enc->html($this->translate('admin', 'Unique page URL, e.g. "/page-name"'));
    ?>
											</div>
										</div>
										<div class="form-group row mandatory">
											<label class="col-sm-4 form-control-label help"><?php 
    echo $enc->html($this->translate('admin', 'Title'));
    ?></label>
											<div class="col-sm-8">
												<input class="form-control item-label" type="text" required="required" tabindex="1"
													name="<?php 
    echo $this->formparam(['item', 'cms.label']);
    ?>"
													placeholder="<?php 
    echo $enc->attr($this->translate('admin', 'Internal name (required)'));
    ?>"
													value="<?php 
    echo $enc->attr($this->get('itemData/cms.label'));
    ?>"
													v-bind:readonly="!can('change')">
											</div>
											<div class="col-sm-12 form-text text-muted help-text">
												<?php 
    echo $enc->html($this->translate('admin', 'Page title, will be used on the web site if no title for the language is available'));
    ?>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>

						<?php 
    echo $this->get('itemBody');
    ?>

					</div>

					<div class="item-actions">
						<?php 
    echo $this->partial($this->config('admin/jqadm/partial/itemactions', 'itemactions'), ['params' => $params]);
    ?>
					</div>
				</div>

			</div>

		<?php 
}
?>

	</div>
</form>

<?php 
$this->block()->stop();
?>


<?php 
echo $this->render($this->config('admin/jqadm/template/page', 'page'));
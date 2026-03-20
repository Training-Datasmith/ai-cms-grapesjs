<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 */
$enc = $this->encoder();
$target = $this->config('admin/jqadm/url/search/target');
$controller = $this->config('admin/jqadm/url/search/controller', 'Jqadm');
$action = $this->config('admin/jqadm/url/search/action', 'search');
$config = $this->config('admin/jqadm/url/search/config', []);
$new_target = $this->config('admin/jqadm/url/create/target');
$new_cntl = $this->config('admin/jqadm/url/create/controller', 'Jqadm');
$new_action = $this->config('admin/jqadm/url/create/action', 'create');
$new_config = $this->config('admin/jqadm/url/create/config', []);
$get_target = $this->config('admin/jqadm/url/get/target');
$get_cntl = $this->config('admin/jqadm/url/get/controller', 'Jqadm');
$get_action = $this->config('admin/jqadm/url/get/action', 'get');
$get_config = $this->config('admin/jqadm/url/get/config', []);
$copy_target = $this->config('admin/jqadm/url/copy/target');
$copy_cntl = $this->config('admin/jqadm/url/copy/controller', 'Jqadm');
$copy_action = $this->config('admin/jqadm/url/copy/action', 'copy');
$copy_config = $this->config('admin/jqadm/url/copy/config', []);
$del_target = $this->config('admin/jqadm/url/delete/target');
$del_cntl = $this->config('admin/jqadm/url/delete/controller', 'Jqadm');
$del_action = $this->config('admin/jqadm/url/delete/action', 'delete');
$del_config = $this->config('admin/jqadm/url/delete/config', []);
/** admin/jqadm/cms/fields
 * List of cms columns that should be displayed in the list view
 *
 * Changes the list of cms columns shown by default in the cms list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "cms.id" for the cms ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2020.10
 * @category Developer
 */
$default = ['cms.status', 'cms.url', 'cms.label'];
$default = $this->config('admin/jqadm/cms/fields', $default);
$fields = $this->session('aimeos/admin/jqadm/cms/fields', $default);
$search_params = $params = $this->get('pageParams', []);
$search_params['page']['start'] = 0;
$search_attributes = map($this->get('filterAttributes', []))->filter(function ($item) {
    return $item->is_public();
})->call('toArray')->each(function (&$val) {
    $val = $this->translate('admin/code', $val['label'] ?? ' ');
})->all();
$operators = map($this->get('filterOperators/compare', []))->flip()->map(function ($val, $key) {
    return $this->translate('admin/code', $key);
})->all();
$column_list = ['cms.id' => $this->translate('admin', 'ID'), 'cms.status' => $this->translate('admin', 'Status'), 'cms.url' => $this->translate('admin', 'URL'), 'cms.label' => $this->translate('admin', 'Title'), 'cms.ctime' => $this->translate('admin', 'Created'), 'cms.mtime' => $this->translate('admin', 'Modified'), 'cms.editor' => $this->translate('admin', 'Editor')];
$this->block()->start('jqadm_content');
?>

<?php 
echo $this->partial($this->config('admin/jqadm/partial/navsearch', 'navsearch'));
echo $this->partial($this->config('admin/jqadm/partial/columns', 'columns'));
?>

<div class="list-view"
	data-domain="cms"
	data-siteid="<?php 
echo $enc->attr($this->site()->siteid());
?>"
	data-filter="<?php 
echo $enc->attr($this->session('aimeos/admin/jqadm/cms/filter', new \stdClass()));
?>"
	data-items="<?php 
echo $enc->attr($this->get('items', map())->call('toArray', [true])->all());
?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?php 
echo $enc->html($this->translate('admin', 'CMS pages'));
?>
			<span class="navbar-secondary">(<?php 
echo $enc->html($this->site()->label());
?>)</span>
		</span>

		<div class="btn icon act-search" v-on:click="search = true"
			title="<?php 
echo $enc->attr($this->translate('admin', 'Show search form'));
?>"
			aria-label="<?php 
echo $enc->attr($this->translate('admin', 'Show search form'));
?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?php 
echo $enc->js($this->link('admin/jqadm/url/search', map($search_params)->except('filter')->all()));
?>`"
		v-bind:filter="<?php 
echo $enc->attr($this->session('aimeos/admin/jqadm/cms/filter', []));
?>"
		v-bind:operators="<?php 
echo $enc->attr($operators);
?>"
		v-bind:name="`<?php 
echo $enc->js($this->formparam(['filter', '_key_', '0']));
?>`"
		v-bind:attributes="<?php 
echo $enc->attr($search_attributes);
?>">
	</nav-search>

	<?php 
echo $this->partial($this->config('admin/jqadm/partial/pagination', 'pagination'), ['pageParams' => $params, 'pos' => 'top', 'total' => $this->get('total'), 'page' => $this->session('aimeos/admin/jqadm/cms/page', [])]);
?>

	<form ref="form" class="list list-cms" method="POST"
		action="<?php 
echo $enc->attr($this->url($target, $controller, $action, $search_params, [], $config));
?>">

		<?php 
echo $this->csrf()->formfield();
?>

		<column-select tabindex="<?php 
echo $this->get('tabindex', 1);
?>"
			name="<?php 
echo $enc->attr($this->formparam(['fields', '']));
?>"
			v-bind:titles="<?php 
echo $enc->attr($column_list);
?>"
			v-bind:fields="<?php 
echo $enc->attr($fields);
?>"
			v-bind:show="columns"
			v-on:close="columns = false">
		</column-select>

		<div class="table-responsive">
			<table class="list-items table table-hover table-striped">
				<thead class="list-header">
					<tr>
						<th class="select">
							<button class="btn icon-menu" type="button" data-bs-toggle="dropdown"
								aria-expanded="false" title="<?php 
echo $enc->attr($this->translate('admin', 'Menu'));
?>">
							</button>
							<ul class="dropdown-menu">
								<li>
									<a class="btn" v-on:click.prevent="batch = true" href="#" tabindex="1">
										<?php 
echo $enc->html($this->translate('admin', 'Edit'));
?>
									</a>
								</li>
								<li>
									<a class="btn" v-on:click.prevent="askDelete(null, $event)" tabindex="1"
										href="<?php 
echo $enc->attr($this->link('admin/jqadm/url/delete', $params));
?>">
										<?php 
echo $enc->html($this->translate('admin', 'Delete'));
?>
									</a>
								</li>
							</ul>
						</th>

						<?php 
echo $this->partial($this->config('admin/jqadm/partial/listhead', 'listhead'), ['fields' => $fields, 'params' => $params, 'data' => $column_list, 'sort' => $this->session('aimeos/admin/jqadm/cms/sort')]);
?>

						<th class="actions">
							<a class="btn icon act-add" tabindex="1"
								href="<?php 
echo $enc->attr($this->link('admin/jqadm/url/create', $params));
?>"
								title="<?php 
echo $enc->attr($this->translate('admin', 'Insert new entry (Ctrl+I)'));
?>"
								aria-label="<?php 
echo $enc->attr($this->translate('admin', 'Add'));
?>">
							</a>

							<a class="btn act-columns icon" href="#" tabindex="<?php 
echo $this->get('tabindex', 1);
?>"
								title="<?php 
echo $enc->attr($this->translate('admin', 'Columns'));
?>"
								v-on:click.prevent.stop="columns = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>

					<?php 
echo $this->partial($this->config('admin/jqadm/partial/listsearch', 'listsearch'), ['fields' => array_merge($fields, ['select']), 'filter' => $this->session('aimeos/admin/jqadm/cms/filter', []), 'data' => ['cms.id' => ['op' => '=='], 'cms.status' => ['op' => '==', 'type' => 'select', 'val' => ['1' => $this->translate('mshop/code', 'status:1'), '0' => $this->translate('mshop/code', 'status:0'), '-1' => $this->translate('mshop/code', 'status:-1'), '-2' => $this->translate('mshop/code', 'status:-2')]], 'cms.url' => [], 'cms.label' => [], 'cms.ctime' => ['op' => '-', 'type' => 'datetime-local'], 'cms.mtime' => ['op' => '-', 'type' => 'datetime-local'], 'cms.editor' => []]]);
?>

					<tr class="batch" style="display: none" v-show="batch">
						<td colspan="<?php 
echo count($fields) + 2;
?>">
							<div class="batch-header">
								<div class="intro">
									<span class="name"><?php 
echo $enc->html($this->translate('admin', 'Bulk edit'));
?></span>
									<span class="count">{{ selected }} <?php 
echo $enc->html($this->translate('admin', 'selected'));
?></span>
								</div>
								<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
									<?php 
echo $enc->html($this->translate('admin', 'Close'));
?>
								</a>
							</div>
							<div class="card">
								<div class="card-header">
									<span><?php 
echo $enc->html($this->translate('admin', 'Basic'));
?></span>
									<button class="btn btn-primary" formaction="<?php 
echo $enc->attr($this->link('admin/jqadm/url/batch', ['resource' => 'cms']));
?>">
										<?php 
echo $enc->html($this->translate('admin', 'Save'));
?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-cms-status" class="form-check-input" type="checkbox" v-on:click="setState('item/cms.status')">
												</div>
												<label class="col-4 form-control-label" for="batch-cms-status">
													<?php 
echo $enc->html($this->translate('admin', 'Status'));
?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/cms.status')"
														name="<?php 
echo $enc->attr($this->formparam(['item', 'cms.status']));
?>">
														<option value=""></option>
														<option value="1"><?php 
echo $enc->html($this->translate('mshop/code', 'status:1'));
?></option>
														<option value="0"><?php 
echo $enc->html($this->translate('mshop/code', 'status:0'));
?></option>
														<option value="-1"><?php 
echo $enc->html($this->translate('mshop/code', 'status:-1'));
?></option>
														<option value="-2"><?php 
echo $enc->html($this->translate('mshop/code', 'status:-2'));
?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
										</div>
									</div>
								</div>
							</div>
							<div class="batch-footer">
								<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
									<?php 
echo $enc->html($this->translate('admin', 'Close'));
?>
								</a>
								<button class="btn btn-primary" formaction="<?php 
echo $enc->attr($this->link('admin/jqadm/url/batch', ['resource' => 'cms']));
?>">
									<?php 
echo $enc->html($this->translate('admin', 'Save'));
?>
								</button>
							</div>
						</td>
					</tr>

					<?php 
foreach ($this->get('items', []) as $id => $item) {
    ?>
						<?php 
    $url = $enc->attr($this->link('admin/jqadm/url/get', ['id' => $id] + $params));
    ?>
						<tr class="list-item <?php 
    echo $this->site()->mismatch($item->get_site_id());
    ?>" data-label="<?php 
    echo $enc->attr($item->get_label());
    ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?php 
    echo $enc->attr($this->formparam(['id', '']));
    ?>"
									value="<?php 
    echo $enc->attr($item->get_id());
    ?>"
									v-on:click="toggle(`<?php 
    echo $enc->js($id);
    ?>`)"
									v-bind:checked="checked(`<?php 
    echo $enc->js($id);
    ?>`)"
									v-bind:disabled="readonly(`<?php 
    echo $enc->js($id);
    ?>`)">
							</td>
							<?php 
    if (in_array('cms.id', $fields)) {
        ?>
								<td class="cms-id"><a class="items-field" href="<?php 
        echo $url;
        ?>"><?php 
        echo $enc->html($item->get_id());
        ?></a></td>
							<?php 
    }
    ?>
							<?php 
    if (in_array('cms.status', $fields)) {
        ?>
								<td class="cms-status"><a class="items-field" href="<?php 
        echo $url;
        ?>"><div class="icon status-<?php 
        echo $enc->attr($item->get_status());
        ?>"></div></a></td>
							<?php 
    }
    ?>
							<?php 
    if (in_array('cms.url', $fields)) {
        ?>
								<td class="cms-url"><a class="items-field" href="<?php 
        echo $url;
        ?>"><?php 
        echo $enc->html($item->get_url());
        ?></a></td>
							<?php 
    }
    ?>
							<?php 
    if (in_array('cms.label', $fields)) {
        ?>
								<td class="cms-label"><a class="items-field" href="<?php 
        echo $url;
        ?>"><?php 
        echo $enc->html($item->get_label());
        ?></a></td>
							<?php 
    }
    ?>
							<?php 
    if (in_array('cms.ctime', $fields)) {
        ?>
								<td class="cms-ctime"><a class="items-field" href="<?php 
        echo $url;
        ?>"><?php 
        echo $enc->html($item->get_time_created());
        ?></a></td>
							<?php 
    }
    ?>
							<?php 
    if (in_array('cms.mtime', $fields)) {
        ?>
								<td class="cms-mtime"><a class="items-field" href="<?php 
        echo $url;
        ?>"><?php 
        echo $enc->html($item->get_time_modified());
        ?></a></td>
							<?php 
    }
    ?>
							<?php 
    if (in_array('cms.editor', $fields)) {
        ?>
								<td class="cms-editor"><a class="items-field" href="<?php 
        echo $url;
        ?>"><?php 
        echo $enc->html($item->editor());
        ?></a></td>
							<?php 
    }
    ?>

							<td class="actions">
								<a class="btn act-copy icon" tabindex="1"
								href="<?php 
    echo $enc->attr($this->link('admin/jqadm/url/copy', ['id' => $id] + $params));
    ?>"
									title="<?php 
    echo $enc->attr($this->translate('admin', 'Copy this entry'));
    ?>"
									aria-label="<?php 
    echo $enc->attr($this->translate('admin', 'Copy'));
    ?>">
								</a>
								<?php 
    if (!$this->site()->readonly($item->get_site_id())) {
        ?>
									<a class="btn act-delete icon" tabindex="1"
										v-on:click.prevent.stop="askDelete(`<?php 
        echo $enc->js($id);
        ?>`, $event)"
										href="<?php 
        echo $enc->attr($this->link('admin/jqadm/url/delete', $params));
        ?>"
										title="<?php 
        echo $enc->attr($this->translate('admin', 'Delete this entry'));
        ?>"
										aria-label="<?php 
        echo $enc->attr($this->translate('admin', 'Delete'));
        ?>">
									</a>
								<?php 
    }
    ?>
							</td>
						</tr>
					<?php 
}
?>
				</tbody>
			</table>
		</div>

		<?php 
if ($this->get('items', map())->is_empty()) {
    ?>
			<?php 
    echo $enc->html(sprintf($this->translate('admin', 'No items found')));
    ?>
		<?php 
}
?>
	</form>

	<?php 
echo $this->partial($this->config('admin/jqadm/partial/pagination', 'pagination'), ['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get('total'), 'page' => $this->session('aimeos/admin/jqadm/cms/page', [])]);
?>

	<confirm-delete v-bind:items="unconfirmed" v-bind:show="dialog"
		v-on:close="confirmDelete(false)" v-on:confirm="confirmDelete(true)">
	</confirm-delete>

</div>
<?php 
$this->block()->stop();
?>

<?php 
echo $this->render($this->config('admin/jqadm/template/page', 'page'));
<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 */
/** admin/jqadm/cms/item/content/config/suggest
 * List of suggested configuration keys in cms content panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 * @category Developer
 */
$enc = $this->encoder();
?>
<div id="content" class="item-content tab-pane fade" role="tablist" aria-labelledby="content">

	<div id="item-content-group"
		data-data="<?php 
echo $enc->attr($this->get('contentData', []));
?>"
		data-images="<?php 
echo $enc->attr($this->get('contentMedia', []));
?>"
		data-siteid="<?php 
echo $this->site()->siteid();
?>"
		data-domain="cms" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="vue:draggable" item-key="cms.id" group="content" :list="items" handle=".act-move">
				<template #item="{element, index}">

					<div class="group-item card" v-bind:class="{mismatch: !can('match', index)}">
						<div v-bind:id="'item-text-group-item-' + index" v-bind:class="element['_show'] ? 'show' : 'collapsed'"
							v-bind:data-target="'#item-text-group-data-' + index" data-bs-toggle="collapse" role="tab" class="card-header header"
							v-bind:aria-controls="'item-text-group-data-' + index" aria-expanded="false" v-on:click="toggle('_show', index)"
							v-on:mousedown="change()">
							<div class="card-tools-start">
								<div class="btn btn-card-header act-show icon" tabindex="<?php 
echo $this->get('tabindex');
?>"
									title="<?php 
echo $enc->attr($this->translate('admin', 'Show/hide this entry'));
?>">
								</div>
							</div>
							<span class="item-label header-label" v-bind:class="{disabled: !active(index)}">{{ label(index) }}</span>
							<div class="card-tools-end">
								<div class="btn btn-card-header act-copy icon" tabindex="<?php 
echo $this->get('tabindex');
?>"
									title="<?php 
echo $enc->attr($this->translate('admin', 'Duplicate entry (Ctrl+D)'));
?>"
									v-on:click.stop="duplicate(index)">
								</div>
								<div v-if="element['cms.lists.siteid'] == siteid && !element['_nosort']"
									class="btn btn-card-header act-move icon" tabindex="<?php 
echo $this->get('tabindex');
?>"
									title="<?php 
echo $enc->attr($this->translate('admin', 'Move this entry up/down'));
?>">
								</div>
								<div v-if="element['cms.lists.siteid'] == siteid"
									class="btn btn-card-header act-delete icon" tabindex="<?php 
echo $this->get('tabindex');
?>"
									title="<?php 
echo $enc->attr($this->translate('admin', 'Delete this entry'));
?>"
									v-on:click.stop="remove(index)">
								</div>
							</div>
						</div>

						<div v-bind:id="'item-text-group-data-' + index" v-bind:class="element['_show'] ? 'show' : 'collapsed'"
							v-bind:aria-labelledby="'item-text-group-item-' + index" role="tabpanel" class="card-block collapse row">

							<input type="hidden" v-model="element['text.id']"
								v-bind:name="`<?php 
echo $enc->js($this->formparam(['content', '_idx_', 'text.id']));
?>`.replace('_idx_', index)">

							<div class="col-xl-6">

								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?php 
echo $enc->html($this->translate('admin', 'Status'));
?></label>
									<div class="col-sm-8">
										<select class="form-select item-status" required="required" tabindex="<?php 
echo $this->get('tabindex');
?>"
											v-bind:name="`<?php 
echo $enc->js($this->formparam(['content', '_idx_', 'text.status']));
?>`.replace('_idx_', index)"
											v-bind:readonly="!can('change', index)"
											v-model="element['text.status']" >
											<option value=""><?php 
echo $enc->html($this->translate('admin', 'Please select'));
?></option>
											<option value="1" v-bind:selected="element['text.status'] == 1" >
												<?php 
echo $enc->html($this->translate('mshop/code', 'status:1'));
?>
											</option>
											<option value="0" v-bind:selected="element['text.status'] == 0" >
												<?php 
echo $enc->html($this->translate('mshop/code', 'status:0'));
?>
											</option>
											<option value="-1" v-bind:selected="element['text.status'] == -1" >
												<?php 
echo $enc->html($this->translate('mshop/code', 'status:-1'));
?>
											</option>
											<option value="-2" v-bind:selected="element['text.status'] == -2" >
												<?php 
echo $enc->html($this->translate('mshop/code', 'status:-2'));
?>
											</option>
										</select>
									</div>
								</div>

							</div>
							<div class="col-xl-6">

								<?php 
if (!($languages = $this->get('pageLangItems', map()))->count() !== 1) {
    ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?php 
    echo $enc->html($this->translate('admin', 'Language'));
    ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select item-languageid" tabindex="<?php 
    echo $enc->attr($this->get('tabindex'));
    ?>"
												v-bind:items="<?php 
    echo $enc->attr($languages->col('locale.language.label', 'locale.language.id')->to_array());
    ?>"
												v-bind:name="`<?php 
    echo $enc->js($this->formparam(['content', '_idx_', 'text.languageid']));
    ?>`.replace('_idx_', index)"
												v-bind:text="`<?php 
    echo $enc->js($this->translate('admin', 'Please select'));
    ?>`"
												v-bind:all="`<?php 
    echo $enc->js($this->translate('admin', 'All'));
    ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['text.languageid']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?php 
    echo $enc->html($this->translate('admin', 'Language of the entered text'));
    ?>
										</div>
									</div>
								<?php 
} else {
    ?>
									<input class="text-langid" type="hidden"
										v-bind:name="`<?php 
    echo $enc->js($this->formparam(['content', '_idx_', 'text.languageid']));
    ?>`.replace('_idx_', index)"
										value="<?php 
    echo $enc->attr($languages->get_code()->first());
    ?>">
								<?php 
}
?>

							</div>

							<div class="col-xl-12">
								<grapesjs tabindex="<?php 
echo $this->get('tabindex');
?>"
									v-bind:config="<?php 
echo $enc->attr($this->get('config', new \stdClass()));
?>"
									v-bind:setup="Aimeos.CMSContent.GrapesJS" v-bind:update="version" v-bind:media="media"
									v-bind:name="`<?php 
echo $enc->js($this->formparam(['content', '_idx_', 'text.content']));
?>`.replace('_idx_', index)"
									v-bind:readonly="!can('change', index)"
									v-bind:value="element['text.content']"
									v-model="element['text.content']"
								></grapesjs>
							</div>

							<div v-on:click="toggle('_ext', index)" class="col-xl-12 advanced" v-bind:class="{'collapsed': !element['_ext']}">
								<div class="card-tools-start">
									<div class="btn act-show icon" tabindex="<?php 
echo $this->get('tabindex');
?>"
										title="<?php 
echo $enc->attr($this->translate('admin', 'Show/hide advanced data'));
?>">
									</div>
								</div>
								<span class="header-label"><?php 
echo $enc->html($this->translate('admin', 'Advanced'));
?></span>
							</div>

							<div v-show="element['_ext']" class="col-xl-6 secondary">

								<?php 
if (!($list_types = $this->get('contentListTypes', map()))->count() !== 1) {
    ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?php 
    echo $enc->html($this->translate('admin', 'List type'));
    ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select listitem-type" tabindex="<?php 
    echo $enc->attr($this->get('tabindex'));
    ?>"
												v-bind:items="<?php 
    echo $enc->attr($list_types->col('cms.lists.type.label', 'cms.lists.type.code')->to_array());
    ?>"
												v-bind:name="`<?php 
    echo $enc->js($this->formparam(['content', '_idx_', 'cms.lists.type']));
    ?>`.replace('_idx_', index)"
												v-bind:text="`<?php 
    echo $enc->js($this->translate('admin', 'Please select'));
    ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['cms.lists.type']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?php 
    echo $enc->html($this->translate('admin', 'Second level type for grouping items'));
    ?>
										</div>
									</div>
								<?php 
} else {
    ?>
									<input class="listitem-type" type="hidden"
										v-bind:name="`<?php 
    echo $enc->js($this->formparam(['content', '_idx_', 'cms.lists.type']));
    ?>`.replace('_idx_', index)"
										value="<?php 
    echo $enc->attr($list_types->get_code()->first());
    ?>">
								<?php 
}
?>

								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?php 
echo $enc->html($this->translate('admin', 'Start date'));
?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-datestart" type="datetime-local" tabindex="<?php 
echo $this->get('tabindex');
?>"
											v-bind:name="`<?php 
echo $enc->js($this->formparam(['content', '_idx_', 'cms.lists.datestart']));
?>`.replace('_idx_', index)"
											placeholder="<?php 
echo $enc->attr($this->translate('admin', 'YYYY-MM-DD hh:mm:ss (optional)'));
?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['cms.lists.datestart']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?php 
echo $enc->html($this->translate('admin', 'The item is only shown on the web site after that date and time'));
?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?php 
echo $enc->html($this->translate('admin', 'End date'));
?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-dateend" type="datetime-local" tabindex="<?php 
echo $this->get('tabindex');
?>"
											v-bind:name="`<?php 
echo $enc->js($this->formparam(['content', '_idx_', 'cms.lists.dateend']));
?>`.replace('_idx_', index)"
											placeholder="<?php 
echo $enc->attr($this->translate('admin', 'YYYY-MM-DD hh:mm:ss (optional)'));
?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['cms.lists.dateend']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?php 
echo $enc->html($this->translate('admin', 'The item is only shown on the web site until that date and time'));
?>
									</div>
								</div>
							</div>

							<div v-show="element['_ext']" class="col-xl-6 secondary">
								<config-table v-bind:tabindex="`<?php 
echo $enc->js($this->get('tabindex'));
?>`"
									v-bind:keys="<?php 
echo $enc->attr($this->config('admin/jqadm/cms/item/content/config/suggest', []));
?>"
									v-bind:name="`<?php 
echo $enc->js($this->formparam(['content', '_idx_', 'config', '_pos_', '_key_']));
?>`.replace('_idx_', index)"
									v-bind:index="index"
									v-bind:readonly="!can('change', index)"
									v-bind:items="element['config']"
									v-on:update:items="element['config'] = $event"
									v-bind:i18n="{
										value: `<?php 
echo $enc->js($this->translate('admin', 'Value'));
?>`,
										option: `<?php 
echo $enc->js($this->translate('admin', 'Option'));
?>`,
										help: `<?php 
echo $enc->js($this->translate('admin', 'Item specific configuration options, will be available as key/value pairs in the templates'));
?>`,
										insert: `<?php 
echo $enc->js($this->translate('admin', 'Insert new entry (Ctrl+I)'));
?>`,
										delete: `<?php 
echo $enc->js($this->translate('admin', 'Delete this entry'));
?>`,
									}">
								</config-table>
							</div>

							<?php 
echo $this->get('contentBody');
?>

						</div>
					</div>
				</template>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add icon" tabindex="<?php 
echo $this->get('tabindex');
?>"
					title="<?php 
echo $enc->attr($this->translate('admin', 'Insert new entry (Ctrl+I)'));
?>"
					v-on:click="add()" >
				</div>
			</div>
		</div>
	</div>
</div>

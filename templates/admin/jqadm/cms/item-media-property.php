<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 */
$enc = $this->encoder();
?>
<div v-show="element['_ext']" class="col-xl-12 secondary">

	<property-table v-if="element['property'] && element['property'].length"
		v-bind:index="index" v-bind:domain="'media'"
		v-bind:siteid="`<?php 
echo $enc->js($this->site()->siteid());
?>`" v-bind:tabindex="`<?php 
echo $enc->js($this->get('tabindex'));
?>`"
		v-bind:types="<?php 
echo $enc->attr($this->get('propertyTypes', map())->col('media.property.type.label', 'media.property.type.code')->to_json(JSON_FORCE_OBJECT));
?>"
		v-bind:languages="<?php 
echo $enc->attr($this->get('pageLangItems', map())->col('locale.language.label', 'locale.language.id')->to_json(JSON_FORCE_OBJECT));
?>"
		v-bind:name="`<?php 
echo $enc->js($this->formparam(['media', '_idx_', 'property', '_propidx_', '_key_']));
?>`"
		v-bind:items="element['property']" v-on:update:property="element['property'] = $event"
		v-bind:i18n="{
			all: `<?php 
echo $enc->js($this->translate('admin', 'All'));
?>`,
			delete: `<?php 
echo $enc->js($this->translate('admin', 'Delete this entry'));
?>`,
			header: `<?php 
echo $enc->js($this->translate('admin', 'Media properties'));
?>`,
			help: `<?php 
echo $enc->js($this->translate('admin', 'Non-shared properties for the media item'));
?>`,
			insert: `<?php 
echo $enc->js($this->translate('admin', 'Insert new entry (Ctrl+I)'));
?>`,
			placeholder: `<?php 
echo $enc->js($this->translate('admin', 'Property value (required)'));
?>`,
			select: `<?php 
echo $enc->js($this->translate('admin', 'Please select'));
?>`
		}">
	</property-table>

	<?php 
echo $this->get('propertyBody');
?>

</div>

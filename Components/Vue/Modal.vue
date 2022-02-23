<template>
	<div class="modal fade bd-example-modal-lg" :id="componentUid" tabindex="-1" role="dialog" aria-labelledby="testTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title dia-secret">{{ componentUid }}</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="model-img modal-body text-center">
					<component :is="modalComponentToRender" :params="modalComponentToRenderParams"></component>
				</div>
			</div>
		</div>
	</div>
</template>

<script>

var diaModal = Object();

import Timer from './Timer.vue';
import Form from './Form2.vue';
import Messages from './Messages.vue';
import Chart from './Chart.vue';

export default {
  props: ['params'],
	components: {
		'dia-timer': Timer,
		'dia-form': Form,
		'dia-messages': Messages,
		'chart': Chart
	},
  data() {
    return Object.assign(diaModal, {
    })
  },
  beforeCreate() {
    diaModal = new Dia();
  },
  beforeMount() {
    f.setComponentParams(this);

		this.modalComponentToRender = this.params['modalComponentToRender'];
		this.modalComponentToRenderParams = this.params['modalComponentToRenderParams'];
		this.componentUid = this.params['componentUid'];
  }
}

</script>
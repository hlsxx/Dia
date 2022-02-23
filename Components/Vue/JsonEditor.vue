<template>
  <div>
    <json-editor-com
      v-model="data" 
      :expandedOnStart="true"
      @json-change="onJsonChange()"
    ></json-editor-com>
  </div>
  <div class="jsoneditor-btns">
    <button
      class="json-save-btn"
      type="button"
      @click="onSave()"
    >Ulozit</button>
  </div>
</template>

<script>
import { Vue3JsonEditor } from '../../../node_modules/vue3-json-editor/dist/vue3-json-editor.esm.prod.mjs'

export default {
  components: {
    "json-editor-com": Vue3JsonEditor
  },
  props: {
    params: {
      type: Object
    }
  },
  data() {
    return {
      data: [],
      tableName: "",
      conditions: []
    }
  },
  methods: {
    onJsonChange() {
      console.log('value:');
    },
    onSave() {
      this.updateData();
    },
    updateData() {
      axios.put('index.php?action=dia_update_input', {
        params: {
          tableName: this.tableName,
          tableId: 1,
          column: "structure",
          data: this.data
        }
      });
      console.log(this.data);
    },
    loadData() {
      axios.post('index.php?action=dia_select&reset=true&unset=structure&json=true', {
        params: {
          tableName: this.tableName,
          conditions: this.conditions
        }
      }).then((res) => {
        if (res.data.status != 'fail') {
          this.data = res.data;
        }
        console.log(this.data);
      })
    }
  },
  mounted() {
    this.tableName = this.params['tableName'];
    this.conditions = this.params['conditions'];

    if (this.params['data'].length > 0) {
      this.data = this.params['data'];
    } else {
      this.loadData();
    }
  }
}
</script>
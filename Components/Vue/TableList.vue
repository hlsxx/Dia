<template>
  <div v-if="showList">
    {{ getTableName }}
    <div v-for='item in getList' :key='item.id' class="card mb-2">
      <div class="card-body">
        <ul class="list-inline">
          <li v-for='element in item' :key='element' v-html='element' class="list-inline-item">
          </li>
          <li v-if="actionButton" class="list-inline-item">
            <button
              @click="emitComponent(item.id, actionButton.params)"
              :class='actionButton.class'
              :style='actionButton.style'
            >{{ actionButton.name }}</button>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div v-else v-html='getHideValue'></div>
</template>

<script>
  export default {
    props: {
      tableName: {
        type: String,
      },
      list: {
        type: Object
      },
      columns: {
        type: Object
      },
      actionButton: {
        type: Object
      },
      hideValue: {
        type: String
      }
    },
    data() {
      return {
        tableNameVar: '',
        listVar: [],
        hideValueVar: '',
        showList: true
      }
    },
    computed: {
      getTableName() {
        if (this.tableNameVar == '') {
          return this.tableName;
        } else {
          return this.tableNameVar;
        }
      },
      getList() {
        if (
          typeof(this.listVar) !== 'undefined' 
          && this.listVar !== null 
          && this.listVar.length > 0
        ) {
          return this.listVar;
        } else {
          return this.list;
        }
      },
      getHideValue() {
        if (this.hideValueVar != '') {
          return this.hideValueVar;
        } else {
          return this.hideValue;
        }
      }
    },
    methods: {
      emitComponent(id, params) {
        // Zmen $id na id z listu
        params['conditions']['where'][Object.keys(params['conditions']['where'])] = id;

        emitter.emit('emitAction' + params.action, params);
      },
      action(params) {
        if (this.tableName == params.tableName) {
          axios.post('index.php?action=dia_select', {
            params: {
              tableName: params.tableName,
              conditions: params.conditions
            }
          }).then((res) => {
            if (res.data.status != 'fail') {
              this.listVar = res.data;
              this.showList = true;
            } else {
              this.showList = false;
              this.hideValueVar = res.data.message;
            }
          })
        }
      },
      setShowList() {
        if (this.hideValue) {
          this.showList = false;
        } else {
          this.showList = true;
        }
      }
    },
    mounted() {
      emitter.on("emitActionTableList", params => {
        this.action(params);
      });

      this.setShowList();
    }
  }
</script>
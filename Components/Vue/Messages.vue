<template>
  <div v-if="editData.length == 0" :id="componentUid + '_cards'">
    <div class="card messages-header p-1 m-1">
      <div class="row">
        <div v-for="col in tableColumns" :key="col" class="col text-left">
          {{ col }}
        </div>
        <div class="col">
        </div>
      </div>
    </div>
    <div v-for="itemData in data" :key="itemData" class="card message-card p-1 m-1">
      <div @click="edit(itemData)" :class="isAnsweredClass(itemData)">
        <template v-for='(item, colName) in itemData' :key='colName'>
          <div v-if="getStructureValue(colName, 'show_in_table')" class="col text-left">
            <template v-if="getStructureValue(colName, 'type') == 'checkbox'">
              <template v-if="item == '0'" >
                <div v-html="getStructureValue(colName, 'checkbox_false', '<i style=\'color:red\' class=\'fas fa-times\'></i>')"></div>
              </template>
              <template v-else>
                <div v-html="getStructureValue(colName, 'checkbox_true', '<i class=\'fas fa-check\'></i>')"></div>
              </template>
            </template>
            <template v-else>
              {{ limitString(item, colName) }}
            </template>
          </div>
        </template>
        <div class="col">
          <i @click="deleteItem(itemData.id)" class="far fa-trash-alt icon-danger"></i>
        </div>
      </div>
    </div>
  </div>
  <div v-else :id="componentUid + '_opened'">
    <div class="card">
      <div class="card-header row p-1" style="margin:0px">
        <div class="col-1">
          <button @click="closeDetail()" class='btn btn-primary'>
            <i class="fas fa-arrow-left color-secondary" aria-hidden="true"></i>
          </button>
        </div>
        <div class="col">
          <h4>{{ editData.subject }}</h4>
        </div>
      </div>
      <div class="card-body">
        <template v-if="Object.keys(answerData).length > 0">
          <div class="row">
            <div class="col-4 text-left">
              <b>Vy</b> ste odpovedal:
            </div>
            <div class="col-8 text-right color-grey">
              {{ answerData.timestamp }}
            </div>
          </div>
          <p class="card-text text-left">{{ answerData.body }}</p>
          <hr/>
        </template>
        <div class="row">
          <div class="col-4 text-left">
            <b>{{ editData.sender }}</b> napísal:
          </div>
          <div class="col-8 text-right color-grey">
            {{ editData.timestamp }}
          </div>
        </div>
        <p class="card-text text-left">{{ editData.body }}</p>
        <template v-if="sendAnswer">
          <textarea v-model="answer" class="form-control" rows="3"></textarea>
          <button @click="sendAnswerFunc(editData)" class="btn btn-primary mt-3">Poslať odpovedať</button>
        </template>
        <button v-if="!sendAnswer && Object.keys(answerData).length == 0" @click="sendAnswer = true" class="btn btn-primary">Odpovedať</button>
      </div>
    </div>
  </div>
</template>

<script>

var diaMessages = Object();

export default {
  props: ['params'],
  data() {
    return Object.assign(diaMessages, {
      editData: [],
      sendAnswer: false,
      answer: "",
      answerData: {}
    })
  },
  methods: {
    limitString(item, colName) {
      var limit = this.getStructureValue(colName, 'limit_string');
      return f.limitString(item, limit);
    },
    getStructureValue(colName, structureParam, defaultReturnParam, addItallic = false) {
      return diaTables.getStructureValue(
        colName, 
        structureParam, 
        defaultReturnParam, 
        this.tableStructure, 
        addItallic
      );
    },
    closeDetail() {
      this.editData = [];
      this.sendAnswer = false;
      diaMessages.loadData(this, this.params['customActions']['loadData']);
    },
    edit(itemData) {
      if (typeof this.params['customActions']['editUrl'] != "undefined") {
        axios.post('index.php?action=' + this.params['customActions']['afterEdit'], {
          tableName: this.tableName,
          data: itemData
        }).then((res) => {
          if (res.data.status != "fail") {
            window.location.href = itemData[this.params['customActions']['editUrl']];
          }
        })
      } else {
        this.editData = itemData;
        this.answerData = {};

        if (this.params['customActions']['editData']) {
          axios.post('index.php?action=' + this.params['customActions']['editData'], {
            tableName: this.tableName,
            data: itemData
          }).then((res) => {
            if (res.data.status != "fail") {
              this.answerData = res.data['data'];
            }
          })
        }
      }
    },
    deleteItem(rowId) {
      window.event.cancelBubble = true;

      diaMessages.itemDelete(
        this.tableName,
        rowId,
        () => {
          diaMessages.loadData(this, this.params['customActions']['loadData']);
        }
      );
    },
    sendAnswerFunc(editData) {
      var data = editData;
      data['answer'] = this.answer;

      axios.post('index.php?action=' + this.params['customActions']['insertData'], {
        tableName: this.tableName,
        rowId: data.id,
        data: data
      }).then((res) => {
        this.sendAnswer = false;
        editData['id_answer'] = res.data;
        this.edit(editData);
      })
    },
     isAnsweredClass(itemData) {
      return {
        'row': true,
        'color-red': itemData.viewed == 0
      }
    }
  },
  beforeCreate() {
    diaMessages = new Dia();
  },
  beforeMount() {
    f.setComponentParams(this);
    f.initComponent(this, false, this.params['customActions']['loadData']);
  }
}

</script>
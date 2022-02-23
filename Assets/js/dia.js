class Dia extends CustomFunctions {

  constructor() {
    super();
    this.currentWebPage = this.getCurrentWebPage();
    this.previousWebPage = this.getPreviousWebPage();
    this.dir = "holes/dia"
    this.fileDir = "";

    this.tableName = "";
    this.data = [];
    this.conditions = [];
    this.tableStructure = [];
    this.error = "";
    this.componentUid = ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );

    this.tableColumns = {};
    this.tableColumnsKeys = [];

    this.formColumns = [];
    this.allTableColumns = [];

    // Array for new form of table
    this.formValues = [];
    this.formLookupsValues = [];
    this.emptyRequiredInputs = [];
  }

  addToUrl(param, data) {
    var url = new URL(window.location.href);
    url.searchParams.set(param, data);
    window.history.pushState({}, '', url);
  };

  deleteFromUrl(paramToDelete) {
    var url = new URL(window.location.href);
    url.searchParams.delete(paramToDelete);
    window.history.pushState({}, '', url);
  };

  getSearchParameters() {
    var prmstr = window.location.search.substr(1);
    return prmstr != null && prmstr != "" ? this.transformToAssocArray(prmstr) : {};
  }

  transformToAssocArray(prmstr) {
    var params = {};
    var prmarr = prmstr.split("&");
    for ( var i = 0; i < prmarr.length; i++) {
      var tmparr = prmarr[i].split("=");
      params[tmparr[0]] = tmparr[1];
    }
    return params;
  }

  getUrlParam(param) {
    var params = this.getSearchParameters();
    return params[param];
  };

  getUrl() {
    return window.location.href;
  }

  getPreviousWebPage() {
    if (typeof this.getUrlParam('previous_page') != 'undefined') {
      return this.getUrlParam('previous_page');
    } else {
      return '';
    }
  }

  getCurrentWebPage() {
    return this.getLastWord(location.pathname, "/");
  }

  checkRequiredInputs(itemsData, tableStructure) {
    var errorInputs = [];
    Object.keys(tableStructure).forEach((item) => {
      if (tableStructure[item]['required']) {
        if (itemsData[item] == '') {
          //alert('TODO: PRAZDNE POVINNE POLIA!');
          errorInputs.push(item);
        }
      }
    })

    return errorInputs;
  }

  itemDelete(tableName, rowId, callback) {
    swal({
      title: "Ste si istý?",
      text: "Naozaj chcete vymazať tento záznam?",
      type: "warning",
      showCancelButton: true,
      cancelButtonClass: "btn btn-secondary",
      confirmButtonClass: "btn btn-danger",
      confirmButtonText: "Áno",
      cancelButtonText: "Nie",
      closeOnConfirm: false,
      closeOnCancel: false,
    },
    function(isConfirm) {
      if (isConfirm) {
        axios.post('index.php?action=dia_delete', {
          tableName: tableName,
          id: rowId
        })
        swal({
          title: "Vymazané",
          text: "Záznam bol vymazaný!",
          type: "success"
        })
        if (typeof callback == "function") {
          callback();
        }
      } else {
        swal("Zrušené", "Záznam nebol vymazaný!", "warning") 
      }
    });
  }

  // Nacitavaj data asynchronne pri komponentach v komponentach
  // musi data nacitat skor nez ich vklada do child komponenty
  async loadData(_this, customAction = "", dataToSet = []) {
    this.emptyRequiredInputs = [];
    customAction = customAction != "" ? customAction : "dia_select";

    await axios.post('index.php?action=' + customAction, {
      params: {
        tableName: _this.tableName,
        conditions: _this.conditions
      }
    }).then((res) => {
      if (res.data.status != 'fail') {
        _this.data = res.data['data'];
        dataToSet.forEach((item) => {
          _this[item] = res.data[item];
        })
      } else {
        _this.error = true;
        console.log(res);
      }
    })
  }

  setComponentParams(_this) {
    _this.tableName = _this.params['tableName'];
    _this.conditions = _this.params['conditions'];

    _this.tableColumns = _this.params['tableColumns'];
    _this.formColumns = _this.params['formColumns'];

    _this.componentName = _this.componentName + "_" + _this.tableName; 


    if (typeof _this.params['fileDir'] == "undefined" || _this.params['fileDir'] == "") {
      _this.fileDir = _this.params['tableName'];
    } else {
      _this.fileDir = _this.params['fileDir'];
    }
  }

  setComponentData(_this, customAction = "", dataToSet = []) {
    if (_this.params['data'].length > 0) {
      _this.data = _this.params['data'];
    } else {
      this.loadData(_this, customAction, dataToSet);
    }
  }

  refactorCustomLinks(_this) {
    if (_this.params['buttons'].length > 0) {
      _this.params['buttons'].forEach((button) => {
        if (button['customLink'].includes('{%')) {
          let cutOne = button['customLink'].substring(button['customLink'].indexOf('{%') + 2);
          let paramToReplace = cutOne.slice(0, cutOne.indexOf('%}'));
          button['customLink'] = button['customLink'].replace("{%" + paramToReplace + "%}", this.getUrlParam(paramToReplace));
        }
      })
    }
  }

  getLookupValues(colName, col) {
    var lookupColumns = this.tableStructure[colName]['lookup_columns'];
    var returnValue = "";

    if (typeof lookupColumns != "undefined") {
      Object.keys(lookupColumns).forEach((key) => {
        returnValue += col[lookupColumns[key]] + " ";
      })
    } else {
      returnValue = col;
    }

    return returnValue;
  }

}
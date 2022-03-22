class Functions {

  /**
   * Limit string length
   * @param {string} item 
   * @param {int} limit 
   * @returns 
   */
  limitString(item, limit) {
    if (limit && item.length > limit) {
      return item.substring(0, limit) + "...";
    } else {
      return item;
    }
  }

  /**
   * 
   * @param {object} color 
   * @param {string} item 
   * @return string radio-btn-color
   */
  getRadioColor(color, item) {
    if (color) {
      return color[item];
    } else {
      return 'light';
    }
  }

  /**
   * Set component param(data)
   * @param {Object} componentObject 
   * @param {string} param 
   */
  setComponentParam(componentObject, param) {
    if (
      typeof componentObject.params[param] != "undefined" 
      && componentObject.params[param] != "" 
    ) {
      componentObject[param] = componentObject.params[param];
    } else {
      componentObject[param] = "";
    }
    
  }

  /**
   * Set all this.params data into vue variables
   * @param {Object} componentObject 
   */
  setComponentParams(componentObject) {
    Object.keys(componentObject['params']).forEach((param) => {
      this.setComponentParam(componentObject, param);
    })
    
    // If fileDir is unset set as table name
    if (componentObject['fileDir'] == "") {
      componentObject['fileDir'] = componentObject.params['tableName'];
    }
  }

  /**
   * Axios get request
   * @param {string} action 
   * @param {object} params 
   * @param {function} success 
   */
  async axiosGet(action = "", params = {}, success) {
    if(action == "") throw new Error("Unknown axiosGet action");

    await axios.get('index.php?action=' + action, {
      params
    }).then((res) => {
      if (typeof success == "function") {
        success(res);
      }
    })
  }

  /**
   * Axios post request
   * @param {string} action 
   * @param {object} params 
   * @param {function} success 
   */
   async axiosPost(action = "", params = {}, success) {
    if(action == "") throw new Error("Unknown axiosPost action");

    await axios.post('index.php?action=' + action, {
      params
    }).then((res) => {
      if (typeof success == "function") {
        success(res);
      }
    })
  }

  /**
   * window.open action
   * @param {string} action 
   * @param {object} params 
   */
  windowOpen(action = "", params = {}) {
    if(action == "") throw new Error("Unknown windowOpen action");

    var paramsQuery = "";

    if (Object.keys(params).length > 0) {
      Object.keys(params).forEach((param) => {
        paramsQuery += "&" + param + "=" + params[param];
      })

      action += paramsQuery;
    }

    window.open("index.php?action=" + action);
  }

  initComponent(_this, initAddForm = false, loadDataAction = "", dataToSet = []) {
    this.axiosGet("dia_get_structure", {
      tableName: _this.structureName ? _this.structureName : _this.tableName
    },
    (res) => {
      if (res.data.status != 'fail') {
        _this.tableStructure = res.data;
        
        var cols = {};
        var formCols = {};
        var colsAll = {};
        _this.tableColumnsKeys = Object.keys(_this.tableStructure);
        _this.tableColumnsKeys.forEach((item) => {
          if (_this.tableStructure[item]['show_in_table']) {
            cols[item] = _this.tableStructure[item]['name_in_table'];
          }
          if (_this.tableStructure[item]['show_in_form']) {
            formCols[item] = _this.tableStructure[item]['name_in_table'];
          }

          if (item != "id") {
            colsAll[item] = (typeof _this.tableStructure[item]['name_in_table'] != "undefined" ? _this.tableStructure[item]['name_in_table'] : item);
          }

          if (initAddForm == true) {
            if (typeof _this.defaultValues[item] != "undefined") {
              _this.formValues[item] = _this.defaultValues[item];
            } else {
              if (_this.tableStructure[item]['type'] == "checkbox") {
                if (_this.tableStructure[item]['default_value'] == true) {
                  _this.formValues[item] = true;
                } else {
                  _this.formValues[item] = false;
                }
              } else if(_this.tableStructure[item]['type'] == "lookup") {
                _this.formValues[item] = "";
                this.axiosGet("dia_get_select", {
                  tableName: _this.tableStructure[item]['lookup_table']
                }, (res) => {
                  _this.formLookupsValues[item] = res.data['data'];
                })
              } else {
                if (_this.tableStructure[item]['auto']) {
                  _this.formValues[item] = 9999;
                } else {
                  _this.formValues[item] = "";
                }
              }  if (_this.tableStructure[item]['type'] == "checkbox") {
                if (_this.tableStructure[item]['default_value'] == true) {
                  _this.formValues[item] = true;
                } else {
                  _this.formValues[item] = false;
                }
              } else if(_this.tableStructure[item]['type'] == "lookup") {
                _this.formValues[item] = "";
                this.axiosGet("dia_get_select", {
                  tableName: _this.tableStructure[item]['lookup_table']
                }, (res) => {
                  _this.formLookupsValues[item] = res.data['data'];
                })
              } else {
                if (_this.tableStructure[item]['auto']) {
                  _this.formValues[item] = 9999;
                } else {
                  _this.formValues[item] = "";
                }
              }
            }
          }
        });
        _this.tableColumns = cols;
        _this.formColumns = formCols;
        _this.allTableColumns = colsAll;

        this.loadData(_this, loadDataAction, dataToSet);
        this.loadLookups(_this);
      }
    })
  }

  loadData(_this, customAction = "", dataToSet = []) {
    this.emptyRequiredInputs = [];
    customAction = customAction != "" ? customAction : "dia_select";

    if (_this["data"].length == 0) {
      this.axiosPost(customAction, {
        tableName: _this.tableName,
        conditions: _this.conditions
      },
      (res) => {
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
  }

  loadLookups(_this) {
    _this.tableColumnsKeys.forEach((item) => {
      if (_this.tableStructure[item]['type'] == "lookup") {
        this.axiosPost("dia_select", {
          tableName: _this.tableStructure[item]['lookup_table'],
          conditions: {}
        },
        (res) => {
          if (res.data.status != 'fail') {
            _this.lookupsValues[
              _this.tableStructure[item]['lookup_table_col']
            ] = [];

            res.data['data'].forEach((itemValue) => {
              _this.lookupsValues[
                _this.tableStructure[item]['lookup_table_col']
              ][itemValue['id']] = itemValue;
            })
          }
        })
      }
    });
  }

  getCurrentLookup(_this, colName, idLookup) {
    if (typeof _this.lookupsValues[colName] != "undefined") {
      return _this.lookupsValues[colName][idLookup];
    } else {
      return {};
    }
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

}
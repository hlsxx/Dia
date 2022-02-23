class CustomFunctions {
  
  getLastWord(stringToCut, delimeter) {
    var n = stringToCut.split(delimeter);
    return n[n.length - 1];
  };
  
}
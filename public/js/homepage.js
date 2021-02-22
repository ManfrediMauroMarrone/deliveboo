/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/homepage.js":
/*!**********************************!*\
  !*** ./resources/js/homepage.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// import Vue from "vue";
// window.onload = function(){
var app = new Vue({
  el: "#app",
  data: {
    dishes: [],
    categories: [],
    restaurants: [],
    selectedRestaurants: [],
    selectedCategories: [],
    url_base: "http://localhost:8000/storage/"
  },
  methods: {
    selectedCategory: function selectedCategory(category_id) {
      var _this = this;

      /* se la categoria è stata selezionata la togli dall'array */
      if (this.selectedCategories.includes(category_id)) {
        this.selectedCategories = this.selectedCategories.filter(function (item) {
          return item !== category_id;
        });
        this.restaurants = this.restaurants.filter(function (item) {
          return item.pivot.category_id !== category_id;
        });

        if (this.restaurants.length == 0) {
          axios.get("http://localhost:8000/api/restaurants").then(function (restaurants) {
            var restaurant = restaurants.data.results;
            _this.restaurants = restaurant;
          });
        }
      } else {
        /* se è la prima categoria selezionata svuoto array e richiamo dati con chiamata all'api */
        if (this.selectedCategories.length == 0) {
          this.restaurants = [];
          axios.get("http://localhost:8000/api/filtered-restaurants/" + category_id).then(function (response) {
            var restaurant = response.data.results;
            _this.restaurants = restaurant;
          });
        } else {
          /* se seleziono una categoria non presente aggiungo i risultati ai dati precedenti */
          axios.get("http://localhost:8000/api/filtered-restaurants/" + category_id).then(function (response) {
            var restaurant = response.data.results;

            var _loop = function _loop(index) {
              restaurant = restaurant.filter(function (item) {
                return item.id !== _this.restaurants[index].id;
              });
            };

            for (var index = 0; index < _this.restaurants.length; index++) {
              _loop(index);
            }

            _this.restaurants = _this.restaurants.concat(restaurant);
          });
        }

        this.selectedCategories.push(category_id);
      }
    }
  },
  mounted: function mounted() {
    var _this2 = this;

    axios.get("http://localhost:8000/api/dishes").then(function (dishes) {
      var dish = dishes.data.results;
      _this2.dishes = dish;
    });
    axios.get("http://localhost:8000/api/categories").then(function (categories) {
      var category = categories.data.results;
      _this2.categories = category;
    });
    axios.get("http://localhost:8000/api/restaurants").then(function (restaurants) {
      var restaurant = restaurants.data.results;
      _this2.restaurants = restaurant;
    });
  }
});

/***/ }),

/***/ 1:
/*!****************************************!*\
  !*** multi ./resources/js/homepage.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\MAMP\htdocs\boolean\deliveboo\resources\js\homepage.js */"./resources/js/homepage.js");


/***/ })

/******/ });
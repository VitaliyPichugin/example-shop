<template >

    <div class="filter-form" v-if="FILTERS.data && FILTERS.data.length">

        <div class="row">
            <h4>Filters</h4>
        </div>
        <div class="row">
            <filter-params v-for="item in FILTERS.data"
                           v-if="item.params.length"
                           :findex="item.id"
                           :key="item.id"
                           :data="item">
            </filter-params>
        </div>
        <div class="row text-right " width="100%">
            <button type="button" v-if="show_prod" @click.prevent="getResults" class="btn px-3 btn-primary-dark-w py-2 mr-1 rounded-sm">Show Products</button>
            <button v-if="(typeof this.$route.query.group_filters !== 'undefined')"  type="button" @click.prevent="clearAllFilters" class="btn px-3 btn-primary-dark-w py-2 mr-1 rounded-sm">Clear all filters</button>
            <div v-if="FILTERS.data[0].cases > cnt_filters">
                <button v-if="(typeof this.$route.query.all_filters === 'undefined')"  type="button" @click.prevent="showMoreFilters" class="btn px-3 btn-primary-dark-w py-2 mr-1 rounded-sm">Show more filters</button>
                <button v-else  type="button" @click.prevent="hideMoreFilters" class="btn px-3 btn-primary-dark-w py-2 mr-1 rounded-sm">Hide more filters</button>
            </div>
        </div>
    </div>
</template>

<script>
import FilterParams from "./Filter";
import {mapActions, mapGetters, mapMutations} from "vuex";
import {eventBus} from "../../app";

export default {
    components: {FilterParams},
    name: "FilterList",
    data: function () {
        return {
            currentLang: {},
            cnt_filters: 12,
            show_prod: false
        }
    },
    created() {
        eventBus.$on('updateFilters', data => {
            this.show_prod = false
            if(this.getParams().length){
                this.show_prod = true
            }
        });
    },
    computed: {
        ...mapGetters([
            'FILTERS',
            'CHECKED_FILTERS'
        ]),
    },
    ...mapActions([
        "UPDATE_FILTERS_BY_CATEGORY",
    ]),

    methods: {
        ...mapMutations([
            'UPDATE_FILTERS',
        ]),
        ...mapActions([
            "UPDATE_FILTERS_BY_CATEGORY",
        ]),
        showMoreFilters: function (){
            let query = Object.assign({}, this.$route.query);
            query.all_filters = 1;
            this.$router.replace({query}).catch(err => {
            });
        },
        hideMoreFilters: function (){
            let query = Object.assign({}, this.$route.query);
            delete query.all_filters;
            this.$router.replace({query}).catch(err => {});
        },
        clearAllFilters: function (){
            this.$router.replace({}).catch(err => {});
        },
        getStrParams() {
            let str_params = '';
            let params = this.getParams();
            if(params.length) {
                for (let p in params) {
                    if (params[p].length) {
                        str_params += params[p].join(',') + '|';
                    }
                }
                return str_params.substring(0, str_params.length - 1);
            }
            return str_params;
        },
        getParams() {
            let arr = new Array();
            this.FILTERS.data.map((item) => {
                let map = [];
                item.params.map((item) => {
                    if (item.checked) {
                        map.push(item.id);
                    }
                })
                if (map.length) {
                    arr.push(map)
                }
            });
            return arr;
        },
        getResults() {
            let query = Object.assign({}, this.$route.query);
            delete query.page;
            this.$router.replace({query}).catch(err => {
            });
            let getGroupIds = this.getStrParams();
            if (getGroupIds !== '') {
                this.$router.push({
                    query: Object.assign({}, this.$route.query, {group_filters: getGroupIds})
                }).catch(err => {})
            } else {
                delete query.group_filters;
                this.$router.replace({query}).catch(err => {
                });
            }
        },
    },
}
</script>

<style scoped>

</style>



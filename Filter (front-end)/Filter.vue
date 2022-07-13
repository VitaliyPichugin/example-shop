<template>
    <div class="col-md-2" style="overflow: hidden; padding-left: 0; padding-right: 5px;" >
        <div class="input-group mb-2 ml-auto mr-2 pl-auto pr-2" >
            <div class="card" style="">
                <div class="card-header" :title="data['title']">{{data['title']}} <span> [{{data['cnt_in_case']}}]</span></div>
                <div class="card-body" >
                    <div v-for="(item, index) in params()" v-if="item.name" :key="index" class="form-check filter-name">
                        <input v-model="item.checked"
                               @change="change()"
                               type="checkbox"
                               :disabled="!item.cnt_prod"
                               :value="item.id"
                               class="form-check-input"
                               :id="item.id">
                        <label class="form-check-label" style="display: inline-block; " :for="item.id">{{`${item.name}`}} <span>[{{item.cnt_prod}}]</span></label>
                    </div>
                </div>
                <div class="card-footer" >
                    <div class="input-group ">
                        <input class="form-control input-group" type="search" :placeholder="getPlaceholder()" v-model="search" @keyup="params">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {eventBus} from "../../app";
import {mapGetters} from "vuex";
export default {
    name: "FilterParam",
    props: ['data', 'findex', 'lang'],
    data: function () {
        return {
            checkedAllFilters: false,
            search: "",
        }
    },
    methods: {
        filterSelected(id) {
            let res = [];
            if (this.$route.query.group_filters) {
                let selected1 = this.$route.query.group_filters.split('|')
                for (let i in selected1) {
                    res[i] = selected1[i].split(',');
                }
                let data = res.reduce((a, b) => [...a, ...b]);
                return data.includes(id.toString());
            } else return false;
        },
        params: function () {
            let res = [];
            if (this.search !== "") {
                let search = new RegExp(this.search, "gi");
                let data = this.data.params;
                res = data.filter(({name}) => name.match(search));
            } else res = this.data.params;
            return res
        },
        change() {
            eventBus.$emit('updateFilters', {})
        },
        unique: function (arr) {
            let result = [];
            for (let i of arr) {
                if (!result.includes(i)) {
                    result.push(i);
                }
            }
            return result;
        },
        checkParamName: function (str) {
            if (~str.search(':') || ~str.search('/') || ~str.search('<'))
                return false;
            else return true;
        },
        checkAll: function () {
            if (this.checkedAllFilters) {
                for (let ids in this.data.params) {
                    this.$parent.checkedFilters[this.findex] = this.data.params[ids]['id']
                }
            } else {
                for (let k in this.$parent.checkedFilters) {
                    for (let ids in this.data.params) {
                        if (this.$parent.checkedFilters[k] === this.data.params[ids]['id']) {
                            delete this.$parent.checkedFilters[k];
                        }
                    }
                }
            }
        },
        getPlaceholder: function () {
            let placeholder = {};
            placeholder.en = 'Type a text';
            placeholder.es = 'Rakstiet tekstu';
            return placeholder[window._locale];
        }
    },
}
</script>

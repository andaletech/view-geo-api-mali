<template>
    <div class="row">
        <div class="col-12">
            <div class="input-group mb-3">
                <input type="text" v-model="searchTerm" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-button-addon">
                <div class="input-group-append">
                    <button :disabled="!searchTerm" @click="searchTerm = ''" class="btn btn-outline-secondary" type="button" id="search-button-addon">Clear</button>
                </div>
            </div>
        </div>
        <div class="col-12">
            <v-select v-model="locationType" :options="locationTypes"></v-select>
        </div>
        <div class="col-12">
            <b-table :fields="listTableFields" :items="filteredLocations" class="table table-striped table-hover" :tbody-tr-class="'show-on-hover-container'">

                <template v-slot:cell(link)="data">
                    <div class="show-on-hover">
                        <a v-on:click="loadLocationsInLocation" :href="'/locations/' + data.item.id+ '/locations'">
                            <i class="fas fa-link" aria-hidden="true"></i>
                        </a> 
                    </div>
                </template>

            </b-table>
        </div>
    </div>
</template>
<script>
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css'

    var listTableFields = [
        { key: 'id', label: 'ID', sortable: true, sortIndex: 10 },
        { key: 'name', label: 'Name', sortable: true, sortIndex: 20 },
        { key: 'location_type_name', label: 'Location Type Name', sortable: true, sortIndex: 30 },
        { key: 'parent_id', label: 'Parent ID', sortable: true, sortIndex: 40 },
        { key: 'link', label: 'Link', sortable: true, sortIndex: 40 }
    ];
    export default {
        props: {
            locations: {
                type: Array,
                defaul: () => { return []; }
            }
        },
        components: {
            'v-select': vSelect
        },
        data: () => {
            return {
                hover: false,
                searchTerm: null,
                listTableFields: listTableFields,
            }
        },
        computed: {
            orderedLocations: function () {
                return _.sortBy(this.locations, function (aLocation) {
                    return aLocation.name.toLowerCase();
                });
            },
            filteredLocations: function(){
                var locations = this.orderedLocations;
                var typeFilter = this.locationType;
                if (typeFilter) {
                    locations = _.filter(
                        locations,
                        function (aLocation) {
                            return _.lowerCase(aLocation.location_type_name) == typeFilter.code;
                        }
                    );
                }
                if (this.searchTerm) {
                    filterValue = _.lowerCase(this.searchTerm);
                    locations =  _.filter(
                        locations,
                        function (aLocation) {
                            return _.includes(_.lowerCase(aLocation.name), filterValue);
                        }
                    );
                }
                return locations;
            }
        },
        methods: {
            loadLocationsInLocation: function(id){
            this.setLoadingData();
            var that = this;
            axiosApi.get('http://geo.apimali.com/v1/locations/'+ id +'/locations', {}, {headers: {'origin': window.location.origin}})
                .then(function (response) {
                    // handle success
                    console.log(response);
                    that.locations = _.get(response, 'data.locations');
                    that.clearLoadingData();
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                    that.clearLoadingData();
                })
                .finally(function () {
                    // always executed
                });
            },
            setLoadingData: function (loading = true) {
            Vue.set(this, 'loadingData', loading ? true : false);
            },
            clearLoadingData: function(){
                this.setLoadingData(false);
            },
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style>
    .show-on-hover {
        visibility: hidden;
    }
    .show-on-hover-container:hover .show-on-hover  {
        visibility: visible;
        -webkit-transition-duration: 10ms;
        transition-duration: 10ms;
    }
</style>
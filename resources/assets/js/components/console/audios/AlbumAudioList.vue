<style scoped>
</style>

<template>
    <div>
        <div class="panel panel-default" v-if="false">
            <div class="panel-heading">
                操作
            </div>
            <div class="panel-body">
                <button @click="batchSwitchPinStatus" class="btn btn-default btn-addon">
                    <i class="fa fa-thumb-tack"></i>
                    切换置顶状态
                </button>
                &nbsp;
                <button class="btn btn-danger btn-addon" @click="batchDelete()">
                    <i class="fa fa-trash-o"></i>
                    删除
                </button>
            </div>
        </div>

        <someline-table
                :order-by="orderBy"
                :sorted-by="sortedBy"
                :orderable-fields="orderableFields"
                :resource-path="resourcePath"
                :get-search-value="getSearchValue"
                @resource-response="onResourceResponse"
                @selection-change="handleSelectionChange"
        >
            <template slot="SomelineTableColumns">
                <!--<el-table-column-->
                <!--type="selection"-->
                <!--width="55">-->
                <!--</el-table-column>-->
                <el-table-column
                        width="60"
                        label="#">
                    <template scope="scope">
                        {{ scope.row.album_id }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="书号">
                    <template scope="scope">
                        {{ scope.row.book_id }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="书籍名称">
                    <template scope="scope">
                        {{ scope.row.name }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="作者">
                    <template scope="scope">
                        {{ scope.row.author }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="演播">
                    <template scope="scope">
                        {{ scope.row.broadcaster }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="集数">
                    <template scope="scope">
                        {{ scope.row.audios_count }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="未通过声音">
                    <template scope="scope">
                        {{ scope.row.rejected_audios_count }}
                    </template>
                </el-table-column>
                <el-table-column
                        width="100"
                        label="操作">
                    <template scope="scope">
                        <a class="btn btn-default btn-sm r-2x"
                           :href="getManageUrl(scope.row)">
                            <i class="fa fa-edit"></i>&nbsp;声音
                        </a>
                    </template>
                </el-table-column>
            </template>
        </someline-table>
    </div>

</template>

<script>
    export default{
        props: [],
        data(){
            return {
                resourcePath: 'albums?include=related_user',

                orderBy: 'album_id',
                sortedBy: 'asc',

                orderableFields: [
                    {
                        name: 'album_id',
                        display: '序号',
                    },
                    {
                        name: 'created_at',
                        display: '创建时间',
                    },
                    {
                        name: 'updated_at',
                        display: '更新时间',
                    },
                ],
                multipleSelection: [],
            }
        },
        computed: {},
        components: {
        },
        mounted(){
            console.log('Component Ready.');

//            this.eventEmit('SomelineTable.doFetchData');
        },
        watch: {},
        events: {},
        methods: {
            getEditUrl(album) {
                return this.url(`/console/albums/${album.album_id}/edit`)
            },
            getManageUrl(album) {
                return this.url(`/console/albums/${album.album_id}/audios`)
            },
            getAssignUrl(album) {
                return this.url(`/console/albums/${album.album_id}/assign`)
            },
            getDetailUrl(album) {
                return this.url(`/console/albums/${album.album_id}`);
            },
            getPinStatusText(item) {
                return item.is_pinned ? '已置顶' : '未置顶';
            },
            handleDelete(row) {
                this.$confirm('此操作将永久删除该专辑, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.$api.delete(`/albums/${row.album_id}`, {})
                        .then((response) => {
                            this.$message.success('删除成功');
                            this.eventEmit('SomelineTable.doFetchData');
                        }, this.handleResponseError);

                });
            },
            getSearchValue(val){
                console.log('getSearchValue: ', val);
                if (val && val.length > 0) {
                    return val + '';
                } else {
                    return '';
                }
            },
            onResourceResponse(response){
                console.log('response: ', response);
                console.log('response url: ', response.request.responseURL);
            },
            handleSelectionChange(val){
                this.multipleSelection = val;
            },
            checkMultipleSelection() {
                if (this.multipleSelection.length <= 0) {
                    this.$message.error('至少选择一条数据');
                    return false;
                }
                return true;
            },
            arrayColumn(array = [], column) {
                let result = [];
                array.forEach(function (item) {
                    result.push(item[column]);
                });
                return result;
            },
            batchSwitchPinStatus() {
                if (!this.checkMultipleSelection()) {
                    return false;
                }
                let ids = this.arrayColumn(this.multipleSelection, 'album_id');
                this.$confirm('确定切换选中的记录的置顶状态吗? 注: 已置顶的会取消置顶,未置顶的会设为置顶.', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.$api.put(`/albums/batch_switch_pin_status`, {
                        ids: ids
                    })
                        .then((response) => {
                            this.$message.success('操作成功');
                            this.eventEmit('SomelineTable.doFetchData');
                        }, this.handleResponseError);

                })
            },
            batchDelete() {
                if (!this.checkMultipleSelection()) {
                    return false;
                }
                let ids = this.arrayColumn(this.multipleSelection, 'album_id');
                this.$confirm('确定批量删除选中的专辑吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.$api.delete(`/albums`, {
                        params: {
                            ids: ids
                        }
                    })
                        .then((response) => {
                            this.$message.success('操作成功');
                            this.eventEmit('SomelineTable.doFetchData');
                        }, this.handleResponseError);

                })
            },
            handleResponseError(error){
                var error_message = '请求失败';
                try {
                    var response_error_message = error.response.data.message;
                    if (response_error_message) {
                        console.error(response_error_message);
                        error_message = this.$options.filters.truncate(response_error_message, 80);
                    }
                } catch (e) {
                    console.error(e.stack);
                }

                this.$message({
                    message: error_message,
                    type: 'error'
                });

            },
        },
    }
</script>
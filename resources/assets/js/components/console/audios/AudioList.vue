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

        <div class="panel panel-default">
            <div class="panel-heading">
                筛选
            </div>
            <div class="panel-body">
                <div class="col-sm-6">
                    <p>状态</p>
                    <select class="form-control" v-model="filter_data.status">
                        <option value="">全部</option>
                        <option value="-1">审核未过</option>
                        <option value="0">待审核</option>
                        <option value="1">已通过</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <p>&nbsp;</p>
                    <button class="btn btn-default w-sm" @click.prevent="doReloadData">筛选</button>
                </div>
            </div>
        </div>

        <someline-table
                :order-by="orderBy"
                :sorted-by="sortedBy"
                :orderable-fields="orderableFields"
                :resource-path="resourcePath"
                :resource-params="resourceParams"
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
                        {{ scope.row.audio_id }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="专辑ID">
                    <template scope="scope">
                        {{ scope.row.album_id }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="声音名称">
                    <template scope="scope">
                        {{ scope.row.name }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="专辑名">
                    <template scope="scope">
                        {{ scope.row.album.data.name }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="时长">
                    <template scope="scope">
                        {{ scope.row.audio_length }}
                    </template>
                </el-table-column>
                <el-table-column
                        v-if="!isRole('publisher')"
                        label="审核人员">
                    <template scope="scope">
                        {{ scope.row.reviewer ? scope.row.reviewer.data.name : '-' }}
                    </template>
                </el-table-column>
                <el-table-column
                        width="100"
                        label="状态">
                    <template scope="scope">
                        {{ scope.row.status_text }}
                    </template>
                </el-table-column>
                <el-table-column
                        width="160"
                        label="创建于">
                    <template scope="scope">
                        {{ scope.row.created_at }}
                    </template>
                </el-table-column>
                <el-table-column
                        width="160"
                        label="更新于">
                    <template scope="scope">
                        {{ scope.row.updated_at }}
                    </template>
                </el-table-column>
                <el-table-column
                        width="100"
                        label="操作">
                    <template scope="scope">
                        <a class="btn btn-default btn-sm r-2x"
                           :href="getDetailUrl(scope.row)">
                            <i class="fa fa-file-text-o"></i>&nbsp;查看
                        </a>
                        <a class="btn btn-default btn-sm r-2x"
                           v-if="isRole('reviewer')"
                           :href="getReviewUrl(scope.row)">
                            <i class="fa fa-legal"></i>&nbsp;审核
                        </a>
                        <!--<a class="btn btn-default btn-sm r-2x"-->
                        <!--:href="getEditUrl(scope.row)">-->
                        <!--<i class="fa fa-edit"></i>&nbsp;编辑-->
                        <!--</a>-->
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
                resourcePath: 'audios',

                orderBy: 'status',
                sortedBy: 'asc',

                filter_data: {
                    status: "",
                },

                orderableFields: [
                    {
                        name: 'audio_id',
                        display: '序号',
                    },
                    {
                        name: 'status',
                        display: '状态',
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
        computed: {
            resourceParams(){
                return {
                    include: 'album,reviewer',
                    status: this.filter_data.status,
                }
            },
        },
        components: {},
        mounted(){
            console.log('Component Ready.');

//            this.eventEmit('SomelineTable.doFetchData');
        },
        watch: {},
        events: {},
        methods: {
            getEditUrl(audio) {
                return this.url(`/console/audios/${audio.audio_id}/edit`)
            },
            getReviewUrl(audio) {
                return this.url(`/console/audios/${audio.audio_id}/review`)
            },
            getDetailUrl(audio) {
                return this.url(`/console/albums/${audio.album_id}/audios`);
            },
            getPinStatusText(item) {
                return item.is_pinned ? '已置顶' : '未置顶';
            },
            handleDelete(row) {
                this.$confirm('此操作将永久删除该声音, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.$api.delete(`/audios/${row.audio_id}`, {})
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
                let ids = this.arrayColumn(this.multipleSelection, 'audio_id');
                this.$confirm('确定切换选中的记录的置顶状态吗? 注: 已置顶的会取消置顶,未置顶的会设为置顶.', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.$api.put(`/audios/batch_switch_pin_status`, {
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
                let ids = this.arrayColumn(this.multipleSelection, 'audio_id');
                this.$confirm('确定批量删除选中的声音吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.$api.delete(`/audios`, {
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
            doReloadData(){
                this.eventEmit('SomelineTable.doFetchData');
            },
        },
    }
</script>
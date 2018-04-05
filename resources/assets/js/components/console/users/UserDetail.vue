<style scoped>
</style>

<template>
    <div v-loading.body="isLoading">
        <div class="panel panel-default" v-if="false">
            <div class="panel-heading">
                操作
            </div>
            <div class="panel-body">
                <!--<button @click="doSwitchPinStatus" class="btn btn-default btn-addon">-->
                <!--<i class="fa fa-thumb-tack"></i>-->
                <!--{{ pinned_text }}-->
                <!--</button>-->
                &nbsp;
                <a :href="editUrl" class="btn btn-default btn-addon">
                    <i class="fa fa-pencil"></i>
                    编辑
                </a>
                &nbsp;
                <!--<span class="pull-right">-->
                <!--<button class="btn btn-danger btn-addon"-->
                <!--@click="doConfirmDelete"-->
                <!--:disabled="isDeleting"-->
                <!--&gt;-->
                <!--<i class="fa fa-trash-o"></i>-->
                <!--删除-->
                <!--</button>-->
                <!--</span>-->
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                用户详情
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered m-b-none">
                    <tbody>
                    <tr>
                        <td class="text-right" style="min-width: 120px">用户ID</td>
                        <td style="min-width: 300px" v-text="item.user_id"></td>
                    </tr>
                    <tr>
                        <td class="text-right">名字</td>
                        <td>{{ item.name }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">登录账号</td>
                        <td>{{ item.username }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">创建时间</td>
                        <td>{{ item.created_at }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">最后更新</td>
                        <td>{{ item.updated_at }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
    export default{
        props: {
            itemId: {
                type: String,
                required: false
            }
        },
        data(){
            return {

                item: {},
                isLoading: false,
                isDeleting: false,

            }
        },
        computed: {
            pinned_text() {
                return this.item.is_pinned ? '已置顶' : '未置顶';
            },
            editUrl() {
                return this.url(`/console/users/${this.item.user_id}/edit`);
            }
        },
        components: {},
        mounted(){
            console.log('Component Ready.');

            this.fetchData();
        },
        watch: {},
        events: {},
        methods: {
            fetchData() {

                if (this.isLoading) {
                    return;
                }
                this.isLoading = true;

                this.$api
                    .get(`users/${this.itemId}`, {
                        params: {
                            include: 'category,tags'
                        }
                    })
                    .then((response) => {

                        this.isLoading = false;
                        this.item = response.data.data;

                    }, this.handleResponseError);
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
            doSwitchPinStatus() {
                console.log('切换状态');
                this.$api
                    .put(`users/${this.item.user_id}/pinned`)
                    .then((response) => {

                        this.$message.success('操作成功');
                        this.pin_status = this.item.is_pinned = !this.item.is_pinned;

                    }, this.handleResponseError)
            },
            doConfirmDelete() {
                this.$confirm('此操作将永久删除该用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.doDelete();

                })
            },
            doDelete(){

                this.isDeleting = true;
                this.$api.delete(`/users/${this.item.user_id}`, {})
                    .then((response) => {

                        this.$message.success('删除成功');
                        this.redirectToUrlFromBaseUrl('/console/users');

                    }, this.handleResponseError)
                    .finally(() => {
                        this.isDeleting = false;
                    })

            }
        },
    }
</script>
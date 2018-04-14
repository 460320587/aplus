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
                <span class="pull-right">
                    <button class="btn btn-danger btn-addon"
                            @click="doConfirmDelete"
                            :disabled="isDeleting"
                    >
                        <i class="fa fa-trash-o"></i>
                        删除
                    </button>
                </span>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                声音详情
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered m-b-none">
                    <tbody>
                    <tr>
                        <td class="text-right" style="min-width: 120px">声音ID</td>
                        <td style="min-width: 300px" v-text="item.audio_id"></td>
                    </tr>
                    <tr>
                        <td class="text-right">专辑ID</td>
                        <td>{{ item.album_id }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">声音标题</td>
                        <td>{{ item.name }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">声音时长</td>
                        <td>{{ item.audio_length }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">专辑名</td>
                        <td>{{ album.name }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">作者</td>
                        <td>{{ album.author }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">演播</td>
                        <td>{{ album.broadcaster }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">演绎形式</td>
                        <td>{{ album.broadcaster_type }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">状态</td>
                        <td>{{ item.status_text }}</td>
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


        <someline-form-panel
                @form-submit="onFormSubmit"
                v-loading.body="isReviewLoading"
        >
            <template slot="PanelHeading">
                审核
            </template>

            <!--<someline-form-group>-->
            <!--<template slot="Label">专辑名</template>-->
            <!--<template slot="ControlArea">-->
            <!--<div class="h4 m-t-xs">{{ album.name }}</div>-->
            <!--</template>-->
            <!--</someline-form-group>-->
            <!--<someline-form-group-line/>-->

            <someline-form-group>
                <template slot="Label">声音</template>
                <template slot="ControlArea">
                    <template v-if="audio">
                        <audio class="w-full" :src="audio.someline_file_url" controls autoplay>
                            Sorry, your browser doesn't support HTML5 audio
                        </audio>
                    </template>
                </template>
                <!--<pre class="m-t-sm m-b-none">{{ form_data.example_files }}</pre>-->
                <table class="table table-responsive table-bordered m-t m-b-none">
                    <thead>
                    <tr>
                        <th>时间</th>
                        <th>审核结果</th>
                        <th>原因</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="review in reviews">
                        <tr>
                            <td>{{ review.created_at }}</td>
                            <td>{{ getReviewResultText(review.review_result) }}</td>
                            <td>{{ review.review_text }}</td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </someline-form-group>
            <someline-form-group-line/>

            <someline-form-group-radio-list
                    name="copyright_radio"
                    :inline="true"
                    :required="true"
                    :items="review_items"
                    v-model="form_data.review_result">
                <template slot="Label">审核结果</template>
            </someline-form-group-radio-list>
            <someline-form-group-line/>

            <template v-if="form_data.review_result == -1">
                <someline-form-group-input
                        placeholder="失败原因"
                        :rounded="false"
                        v-model="form_data.review_text"
                        :required="true"
                >
                    <template slot="Label">失败原因</template>
                </someline-form-group-input>
                <someline-form-group-line/>
            </template>

            <someline-form-group>
                <template slot="ControlArea">
                    <button type="submit" class="btn btn-primary" :disabled="isReviewLoading">
                        保存
                        <template v-if="disableSeconds > 0 && form_data.review_result != -1">
                            ({{ disableSeconds }})
                        </template>
                    </button>
                </template>
                <!--<pre class="m-t-sm m-b-none">{{ form_data }}</pre>-->
            </someline-form-group>

        </someline-form-panel>

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

                review_items: [
                    {
                        text: '未审核',
                        value: 0,
                    },
                    {
                        text: '已审核',
                        value: 1,
                    },
                    {
                        text: '审核未过',
                        value: -1,
                    },
                ],

                form_data: {
                    type: 'audio',
                    reviewable_type: 'Audio',
                    reviewable_id: null,
                    is_approved: false,
                    review_result: 0,
                    review_text: null,
                    remarks: null,
                },


                disableSeconds: 0,
                isLoading: false,
                isReviewLoading: false,
                isDeleting: false,

            }
        },
        computed: {
            pinned_text() {
                return this.item.is_pinned ? '已置顶' : '未置顶';
            },
            editUrl() {
                return this.url(`/console/audios/${this.item.audio_id}/edit`);
            },
            audio(){
                return this.item;
            },
            album(){
                try {
                    return this.item.album.data;
                } catch (e) {
                    return {}
                }
            },
            reviews(){
                try {
                    return this.item.reviews.data;
                } catch (e) {
                    return []
                }
            },
            isNewReview(){
                return this.reviews.length == 0;
            },
        },
        components: {},
        mounted(){
            console.log('Component Ready.');

            this.form_data.reviewable_id = this.itemId;

            this.fetchData();
        },
        watch: {
            'disableSeconds': function () {
                if (this.disableSeconds > 0) {
                    setTimeout(() => {
                        this.disableSeconds--;
                    }, 1000)
                }
            }
        },
        events: {},
        methods: {
            getReviewResultText(review_result){
                var results = {
                    '-1': '审核未过',
                    '0': '未审核',
                    '1': '已审核',
                }
                return results[review_result];
            },
            fetchData() {

                if (this.isLoading) {
                    return;
                }
                this.isLoading = true;

                this.$api
                    .get(`audios/${this.itemId}`, {
                        params: {
                            include: 'album,reviews'
                        }
                    })
                    .then((response) => {

                        this.isLoading = false;
                        this.item = response.data.data;

                        if (this.isNewReview) {
                            this.disableSeconds = Math.round(parseInt(this.audio.audio_length) * 0.8)
                        }

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
                    .put(`audios/${this.item.audio_id}/pinned`)
                    .then((response) => {

                        this.$message.success('操作成功');
                        this.pin_status = this.item.is_pinned = !this.item.is_pinned;

                    }, this.handleResponseError)
            },
            doConfirmDelete() {
                this.$confirm('此操作将永久删除该声音, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.doDelete();

                })
            },
            doDelete(){

                this.isDeleting = true;
                this.$api.delete(`/audios/${this.item.audio_id}`, {})
                    .then((response) => {

                        this.$message.success('删除成功');
                        this.redirectToUrlFromBaseUrl('/console/audios');

                    }, this.handleResponseError)
                    .finally(() => {
                        this.isDeleting = false;
                    })

            },
            onFormSubmit(){
                console.log('onFormSubmit');

                this.isReviewLoading = true;

                this.form_data.is_approved = (this.form_data.review_result == 1);

                this.$api.post(`/reviews`, this.form_data)
                    .then(this.handleCreatedResponseSuccess, this.handleResponseError)
                    .finally(() => {
                        this.isReviewLoading = false;
                    });

            },
            handleCreatedResponseSuccess(response) {
                this.$message({
                    message: '保存成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/audios/review`);
            },
        },
    }
</script>
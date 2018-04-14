<style scoped>
</style>

<template>

    <someline-form-panel
            @form-submit="onFormSubmit"
            v-loading.body="isLoading"
    >
        <template slot="PanelHeading">
            专辑信息
        </template>

        <someline-form-group>
            <template slot="Label">书号</template>
            <template slot="ControlArea">
                <div class="row">
                    <div class="col-xs-8">
                        <someline-form-control-input
                                placeholder="书号"
                                v-model="form_data.book_id"
                        />
                    </div>
                    <div class="col-xs-4">
                        <button type="button"
                                :disabled="!this.form_data.book_id"
                                @click.prevent="doFetchBookInfo" class="btn btn-default padder-lg">搜索
                        </button>
                    </div>
                </div>
            </template>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="书籍名称"
                :rounded="true"
                v-model="form_data.name"
                :required="true"
        >
            <template slot="Label">书籍名称</template>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="作者"
                v-model="form_data.author"
                :required="true"
        >
            <template slot="Label">作者</template>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="演播"
                v-model="form_data.broadcaster"
                :required="true"
        >
            <template slot="Label">演播</template>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="broadcaster_radio"
                :items="broadcaster_items"
                v-model="form_data.broadcaster_type">
            <template slot="Label">演绎形式</template>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <someline-form-group-image-upload v-model="form_data.images_data"
                                          :is-model-use-id="false"
                                          :multiple="true"
                                          :limit-size="10000"
                                          :max-image="10"
        >
            <template slot="Label">封面</template>
        </someline-form-group-image-upload>
        <someline-form-group-line/>

        <someline-form-group-text-area
                height="200px"
                v-model="form_data.brief"
        >
            <template slot="Label">内容简介</template>
        </someline-form-group-text-area>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="payment_radio"
                :inline="true"
                :items="payment_items"
                v-model="form_data.payment_type">
            <template slot="Label">付费方式</template>
            <div class="row m-t">
                <div class="col-sm-6" v-if="form_data.payment_type != 2">
                    <div class="input-group">
                        <input type="number" class="form-control" placeholder="价格"
                               v-model="form_data.payment_amount">
                        <span class="input-group-addon">元／小时</span>
                    </div>
                </div>
                <div class="col-sm-6" v-if="form_data.payment_type != 1">
                    <div class="input-group">
                        <input type="number" class="form-control" placeholder="比例"
                               v-model="form_data.payment_percentage">
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
            </div>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">书籍分类</template>
            <template slot="ControlArea">
                <el-cascader
                        style="width: 100%"
                        :options="someline_categories"
                        :props="categories_props"
                        change-on-select
                        v-model="form_data.someline_category_ids"
                ></el-cascader>
            </template>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">关键词</template>
            <template slot="ControlArea">
                <input type="text" class="form-control"
                       placeholder="请输入关键词"
                       @keydown.enter.prevent="doAddKeyword"
                       v-model="keyword"/>
            </template>
            <template slot="HelpText">输入关键词，按回车即可添加</template>
            <template v-for="keyword in form_data.keywords_data">
                <a href="#" @click.prevent="doRemoveKeyword(keyword)" class="m-r-sm m-b-sm">
                    <span class="label bg-info">{{ keyword }}</span>
                </a>
            </template>
            <!--<pre>{{ form_data.keywords_data }}</pre>-->
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="copyright_radio"
                :items="copyright_items"
                v-model="form_data.copyright">
            <template slot="Label">版权方</template>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">分配用户</template>
            <template slot="ControlArea">
                <el-select v-model="form_data.related_user_id" style="width: 100%" clearable filterable placeholder="请选择分配的用户">
                    <el-option
                            v-for="item in users"
                            :key="item.user_id"
                            :label="item.name"
                            :value="item.user_id">
                    </el-option>
                </el-select>
            </template>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="status_radio"
                :inline="true"
                :items="status_items"
                v-model="form_data.status">
            <template slot="Label">状态</template>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="ControlArea">
                <button type="submit" class="btn btn-primary">保存</button>
            </template>
            <!--<pre class="m-t-sm m-b-none">{{ form_data }}</pre>-->
        </someline-form-group>

    </someline-form-panel>

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

                isLoading: false,

                broadcaster_items: [
                    {
                        text: '男单播',
                    },
                    {
                        text: '女单播',
                    },
                    {
                        text: '男女双播',
                    },
                    {
                        text: '多人小说剧',
                    },
                    {
                        text: '广播剧',
                    },
                ],

                copyright_items: [
                    {
                        text: '米赢',
                    },
                    {
                        text: '掌阅',
                    },
                ],

                status_items: [
                    {
                        text: '连载中',
                        value: '0',
                    },
                    {
                        text: '已完结',
                        value: '1',
                    },
                ],

                payment_items: [
                    {
                        text: '买断',
                        value: '1',
                    },
                    {
                        text: '分成',
                        value: '2',
                    },
                    {
                        text: '保底分成',
                        value: '3',
                    },
                ],

                single_checkbox_items: [
                    {
                        text: '置顶',
                        value: 'yes',
                    }
                ],

                editor: null,
                keyword: null,

                form_data: {
                    book_id: null,
                    related_user_id: null,
                    name: null,
                    author: null,
                    broadcaster: null,
                    broadcaster_type: "男单播",
                    someline_category_ids: [],
                    images_data: [],
                    brief: null,
                    payment_type: '1',
                    payment_amount: null,
                    payment_percentage: null,
                    keywords_data: [],
                    copyright: '米赢',
                    status: '0',
                },

                data: null,
                data_loaded: 0,
                remoteTagsLoading: false,

                users: [],
                someline_categories: [],
                categories_props: {
                    value: 'someline_category_id',
                    label: 'category_name',
                    children: 'children'
                }

            }
        },
        computed: {
            inEditMode(){
                return !!this.itemId;
            }
        },
        components: {},
        mounted(){
            console.log('Component Ready.');

            this.fetchCategoryData();
            this.fetchUserData();

        },
        watch: {
            'data_loaded': function () {

                if (this.data_loaded == 2 && this.inEditMode) {
                    this.fetchData();
                } else {
                    this.isLoading = false;
                }

            }
        },
        events: {},
        methods: {
            fetchData() {
                console.log('fetchData');

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get(`albums/${this.itemId}`, {
                        params: {
                            include: 'tags',
                            edit: true,
                        }
                    })
                    .then(this.updateDataFromResponse, this.handleResponseError)
                    .finally(this.handleFormResponseComplete);
            },
            fetchCategoryData() {

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get('categories', {
                        params: {
                            type: 'Album',
                            all_children: true
                        }
                    })
                    .then((response) => {
                        this.someline_categories = response.data.data;
                        this.data_loaded += 1;
                    }, this.handleResponseError)

            },
            fetchUserData() {

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get('users/all', {
                        params: {
//                            type: 'audio',
//                            all_children: true
                        }
                    })
                    .then((response) => {
                        this.users = response.data.data;
                        this.data_loaded += 1;
                    }, this.handleResponseError)

            },
            updateDataFromResponse(response){
                let data = response.data.data;

                this.form_data = Object.assign({}, this.form_data, data);
//                this.form_data.someline_image = data.someline_image_url;
//                this.form_data.someline_category_id = data.someline_category_id;

            },
            arrayColumn(array = [], column) {
                let result = [];
                array.forEach(function (item) {
                    result.push(item[column]);
                });
                return result;
            },
            onFormSubmit(){
                console.log('onFormSubmit');

                this.isLoading = true;

                if (this.inEditMode) {
                    this.$api.put(`/albums/${this.form_data.album_id}`, this.form_data)
                        .then(this.handleUpdatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                } else {
                    this.$api.post('albums', this.form_data)
                        .then(this.handleCreatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                }

            },
            handleCreatedResponseSuccess(response) {
                this.$message({
                    message: '保存成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/albums/${response.data.data.album_id}`);
            },
            handleUpdatedResponseSuccess(response) {
                this.$message({
                    message: '更新成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/albums/${this.itemId}`);
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
            handleFormResponseComplete(){

                this.isLoading = false;

            },
            doFetchBookInfo(){

                this.isLoading = true;

                if (!this.form_data.book_id) {
                    return;
                }

                this.$api
                    .get(`zhangyue/books/${this.form_data.book_id}`, {
                        params: {
//                            include: 'tags',
//                            edit: true,
                        }
                    })
                    .then((response) => {
                        let data = response.data.data;
                        if (data.name) {
                            this.form_data.name = data.name;
                        }
                        if (data.author) {
                            this.form_data.author = data.author;
                        }
//                        this.form_data.broadcaster = data.broadcaster;
//                        this.form_data.broadcaster_type = data.broadcaster_type;
//                        this.form_data.someline_category_ids = data.someline_category_ids;
//                        this.form_data.images_data = data.images_data;
                        if (data.brief) {
                            this.form_data.brief = data.brief;
                        }
//                        this.form_data.payment_type = data.payment_type;
//                        this.form_data.payment_amount = data.payment_amount;
//                        this.form_data.payment_percentage = data.payment_percentage;
                        if (data.keywords && data.keywords.length > 0) {
                            this.form_data.keywords_data = data.keywords.split(',');
                        }
                    }, () => {
                        this.$message({
                            message: '无法获取该书籍信息',
                            type: 'error'
                        });
                    })
                    .finally(this.handleFormResponseComplete);

            },
            doAddKeyword(){
//                console.log('doAddKeyword');

                var keyword = this.keyword;
                if (!keyword || keyword.length == 0) {
                    return;
                }
//                console.log('this.keyword', keyword);
//                console.log('this.form_data.keywords_data.indexOf(this.keyword)', this.form_data.keywords_data.indexOf(keyword));
                if (this.form_data.keywords_data.indexOf(keyword) == -1) {
                    this.form_data.keywords_data.push(keyword);
                }
                this.keyword = null;

            },
            doRemoveKeyword(keyword){
                this.form_data.keywords_data.splice(this.form_data.keywords_data.indexOf(keyword), 1)
            }
        },
    }
</script>
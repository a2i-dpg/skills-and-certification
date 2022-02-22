<script src="https://twitter.github.io/typeahead.js/js/handlebars.js"></script>
<script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
<script>
    $(function () {
        $('.typeahead-ajax-custom').each(function () {
            const formElem = $(this).closest('form');
            const filterConditions = {};
            const url = $(this).data('url');
            const model = $(this).data('model');
            const extractType = $(this).data('extract-type');
            if (!model || !url) return {};
            let query = {
                model: model
            };

            const dependOn = $(this).data('depend-on');
            if (dependOn && Array.isArray(dependOn)) {
                dependOn.map(function (itemVal) {
                    if (extractType && extractType === 'id') {
                        return formElem.find("#" + itemVal);
                    }
                    return formElem.find("[name='" + itemVal + "']");
                }).filter(function (elements) {
                    return elements.length > 0 && elements.val() && elements.val().length > 0;
                }).forEach(function (element) {
                    let propertyName;
                    if (extractType && extractType === 'id') {
                        propertyName = element.prop('id');
                    } else {
                        propertyName = element.prop('name');
                    }
                    filterConditions[propertyName] = element.val();
                });
            } else if (dependOn && typeof dependOn === 'object') {
                $.each(dependOn, function (key, dbFieldName) {
                    let element;
                    if (extractType && extractType === 'id') {
                        element = formElem.find("#" + key);
                    } else {
                        element = formElem.find("[name='" + key + "']");
                    }

                    if (element.length > 0 && element.val().length > 0) {
                        filterConditions[dbFieldName] = element.val();
                    }
                });
            }
            if (dependOn && Object.keys(filterConditions).length === 0) return false;
            let relation = $(this).data('relation');
            let relationDependOn = $(this).data('relation-depend-on');
            let relationFilterConditions = {};

            if (relation && relationDependOn && typeof relationDependOn === 'object') {
                $.each(relationDependOn, function (key, dbFieldName) {
                    let element;
                    if (extractType && extractType === 'id') {
                        element = formElem.find("#" + key);
                    } else {
                        element = formElem.find("[name='" + key + "']");
                    }

                    if (element.length > 0 && element.val() && element.val().length > 0) {
                        relationFilterConditions[dbFieldName] = element.val();
                    }
                });
            }
            let additionalQueries = $(this).data('additional-queries');

            if (additionalQueries && typeof additionalQueries === 'object') {
                $.each(additionalQueries, function (key, val) {
                    if (key && val) filterConditions[key] = val;
                });
            }
            if (Object.keys(filterConditions).length > 0) {
                query['filters'] = filterConditions;
            }
            if (Object.keys(relationFilterConditions).length > 0) {
                query['relation_filters'] = relationFilterConditions;
                query['relation'] = relation;
            }
            let label = $(this).data('label');
            if (label) query['label'] = label;
            let key = $(this).data('key');
            if (key) query['key'] = key;

            let additionalLabels = $(this).data('additional-labels');
            if (additionalLabels && Array.isArray(additionalLabels) && additionalLabels.length) {
                query['additional_fields'] = additionalLabels;
            }

            let searchFields = $(this).data('search-fields');
            if (searchFields && Array.isArray(searchFields) && searchFields.length) {
                query['search_fields'] = searchFields;
            }

            let eloScope = $(this).data('scope');
            if (eloScope) {
                query['scope'] = eloScope;
            }

            const bloodhoundAdapter = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                // prefetch: 'https://twitter.github.io/typeahead.js/data/films/post_1960.json',
                remote: {
                    url: url,
                    prepare: function (q, rq) {
                        rq.data = Object.assign({}, {search: q}, query);
                        rq.type = 'POST';
                        return rq;
                    },
                    transport: function (opts, onSuccess, onError) {
                        return $.ajax(opts).done(onSuccess).fail(onError);
                    }
                }
            });

            $(this).typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                display: 'value',
                limit: 10,
                source: bloodhoundAdapter.ttAdapter(),
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'Not found any.',
                        '</div>'
                    ].join('\n'),
                    suggestion: function (data) {
                        return '<p>' + data.value + '</p>';
                    }
                }
            });
        });
    });
</script>

import {Component, Input, OnInit} from '@angular/core';
import {LanguageStore} from '@store/language/language.store';
import {LineAction, ListViewMeta} from '@store/metadata/metadata.store.service';
import {Record} from '@store/list-view/list-view.store';

@Component({
    selector: 'scrm-line-action-menu',
    templateUrl: 'line-action-menu.component.html'
})

export class LineActionMenuComponent implements OnInit {

    @Input() listMetaData: ListViewMeta;
    @Input() record: Record;

    constructor(protected languageStore: LanguageStore) {
    }

    ngOnInit(): void {
    }

    getLineActions(): LineAction[] {
        const actions = [];

        this.listMetaData.lineActions.forEach(action => {
            const recordAction = {...action};

            const params: { [key: string]: any } = {};
            /* eslint-disable camelcase,@typescript-eslint/camelcase*/
            params.return_module = action.legacyModuleName;
            params.return_action = 'index';
            /* eslint-enable camelcase,@typescript-eslint/camelcase */
            params[action.mapping.moduleName] = action.legacyModuleName;

            params[action.mapping.name] = this.record.attributes.name;
            params[action.mapping.id] = this.record.id;
            recordAction.link = {
                label: action.labelKey,
                url: null,
                route: '/' + action.module + '/' + action.action,
                params
            };

            actions.push(recordAction);
        });
        return actions;
    }
}

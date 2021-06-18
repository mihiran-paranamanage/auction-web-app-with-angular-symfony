import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import {ItemsComponent} from './public/items/items.component';
import {AddItemComponent} from './public/add-item/add-item.component';
import {PageNotFoundComponent} from './shared/page-not-found/page-not-found.component';
import {AutoBidConfigComponent} from './public/auto-bid-config/auto-bid-config.component';
import {ItemDetailsComponent} from './public/item-details/item-details.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'items',
    pathMatch: 'full'
  },
  {
    path: 'items/add',
    component: AddItemComponent,
  },
  {
    path: 'items',
    component: ItemsComponent,
  },
  {
    path: 'autoBidConfig',
    component: AutoBidConfigComponent,
  },
  {
    path: 'items/bid',
    component: ItemDetailsComponent,
  },
  {
    path: '**',
    component: PageNotFoundComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

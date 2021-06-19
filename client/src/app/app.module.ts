import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppRoutingModule } from './app-routing.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatTableModule } from '@angular/material/table';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatSortModule } from '@angular/material/sort';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatCardModule } from '@angular/material/card';
import { MatDividerModule } from '@angular/material/divider';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatButtonModule } from '@angular/material/button';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatListModule } from '@angular/material/list';
import { MatIconModule } from '@angular/material/icon';
import { CurrencyPipe } from '@angular/common';
import {ReactiveFormsModule} from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import {MatBottomSheetModule} from '@angular/material/bottom-sheet';
import {MatDialogModule} from '@angular/material/dialog';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';

import { AppComponent } from './app.component';
import { ItemListComponent } from './public/item-list/item-list.component';
import { NavbarComponent } from './shared/navbar/navbar.component';
import { ItemsComponent } from './public/items/items.component';
import { SidenavMenuItemsComponent } from './shared/sidenav-menu-items/sidenav-menu-items.component';
import { AddItemComponent } from './public/add-item/add-item.component';
import { PageNotFoundComponent } from './shared/page-not-found/page-not-found.component';
import { ItemUpdateComponent } from './public/item-update/item-update.component';
import { ItemDeleteComponent } from './public/item-delete/item-delete.component';
import { ItemActionsComponent } from './public/item-actions/item-actions.component';
import { ItemActionDeleteConfirmationComponent } from './public/item-action-delete-confirmation/item-action-delete-confirmation.component';
import { ItemDetailsFormComponent } from './public/item-details-form/item-details-form.component';
import {MatDatepickerModule} from '@angular/material/datepicker';
import { ItemBidComponent } from './public/item-bid/item-bid.component';
import { AutoBidConfigComponent } from './public/auto-bid-config/auto-bid-config.component';
import {MatCheckboxModule} from '@angular/material/checkbox';
import { ItemDetailsComponent } from './public/item-details/item-details.component';
import { BidHistoryComponent } from './public/bid-history/bid-history.component';
import { HomePageComponent } from './public/home-page/home-page.component';
import { LoginPageComponent } from './public/login-page/login-page.component';
import { MainComponent } from './main/main/main.component';
import { ForbiddenComponent } from './shared/forbidden/forbidden.component';

@NgModule({
  declarations: [
    AppComponent,
    ItemListComponent,
    NavbarComponent,
    ItemsComponent,
    SidenavMenuItemsComponent,
    AddItemComponent,
    PageNotFoundComponent,
    ItemUpdateComponent,
    ItemDeleteComponent,
    ItemActionsComponent,
    ItemActionDeleteConfirmationComponent,
    ItemDetailsFormComponent,
    ItemBidComponent,
    AutoBidConfigComponent,
    ItemDetailsComponent,
    BidHistoryComponent,
    HomePageComponent,
    LoginPageComponent,
    MainComponent,
    ForbiddenComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    MatToolbarModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    MatFormFieldModule,
    MatInputModule,
    MatCardModule,
    MatDividerModule,
    MatGridListModule,
    MatButtonModule,
    MatSnackBarModule,
    MatSidenavModule,
    MatListModule,
    MatIconModule,
    ReactiveFormsModule,
    HttpClientModule,
    MatBottomSheetModule,
    MatDialogModule,
    NoopAnimationsModule,
    MatDatepickerModule,
    MatCheckboxModule
  ],
  providers: [
    CurrencyPipe,
    MatDatepickerModule
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }

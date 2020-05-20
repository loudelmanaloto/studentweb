import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ContainersModule } from '../containers/containers.module';
import { MainComponent } from './main.component';
import { MainRoutingModule } from './main-routing.module';
import { SweetAlert2Module } from '@sweetalert2/ngx-sweetalert2';


import { MatSidenavModule } from '@angular/material/sidenav';
import { MatIconModule } from '@angular/material/icon';
import { MatListModule } from '@angular/material/list';
import { MatTableModule } from '@angular/material/table';
import { MatSortModule } from '@angular/material/sort';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatDialogModule } from '@angular/material/dialog';
import { MatCardModule } from '@angular/material/card';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatTabsModule } from '@angular/material/tabs';
import { ClipboardModule } from '@angular/cdk/clipboard';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatMenuModule } from '@angular/material/menu';



import { DashboardComponent } from '../dashboard/dashboard.component';
import { AnnouncementsComponent } from '../announcements/announcements.component';
import { ClassesComponent } from '../classes/classes.component';
import { ForumsComponent } from '../forums/forums.component';
import { ProfileComponent, DialogOverviewExampleDialog } from '../profile/profile.component';
import { StudentListComponent } from '../student-list/student-list.component';
import { MatPaginatorModule } from '@angular/material/paginator';
import { ManageAnnouncementComponent } from '../manage-announcement/manage-announcement.component';
import { ClassroomComponent } from '../classroom/classroom.component';
import { MatRippleModule } from '@angular/material/core';
import { FormsModule } from '@angular/forms';
import { MessageComponent } from '../message/message.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ContainersModule,
    MainRoutingModule,
    MatSidenavModule,
    MatIconModule,
    MatListModule,
    MatTableModule,
    MatSortModule,
    MatFormFieldModule,
    MatInputModule,
    MatPaginatorModule,
    MatButtonModule,
    MatDialogModule,
    MatRippleModule,
    SweetAlert2Module,
    MatCardModule,
    MatGridListModule,
    MatToolbarModule,
    MatTabsModule,
    ClipboardModule,
    MatTooltipModule,
    MatMenuModule
  ],
  declarations: [
    MainComponent,
    DashboardComponent,
    AnnouncementsComponent,
    ClassesComponent,
    ForumsComponent,
    ProfileComponent,
    StudentListComponent,
    ManageAnnouncementComponent,
    ClassroomComponent,
    DialogOverviewExampleDialog,
    MessageComponent
  ],
  entryComponents: [ManageAnnouncementComponent],
})
export class MainModule {}

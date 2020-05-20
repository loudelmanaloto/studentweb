import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DashboardComponent } from '../dashboard/dashboard.component';
import { AnnouncementsComponent } from '../announcements/announcements.component';
import { ClassesComponent } from '../classes/classes.component';
import { ForumsComponent } from '../forums/forums.component';
import { ProfileComponent } from '../profile/profile.component';
import { StudentListComponent } from '../student-list/student-list.component';
import { ClassroomComponent } from '../classroom/classroom.component';
import { AuthGuard } from '../services/auth.guard';

export const MainRoutes: Routes = [
  //CLASSROOM SUBCOMPONENTS ROUTING:
  { path: 'classroom', component: ClassroomComponent },
  //END OF CLASSROOM SUBCOMPONENTS ROUTING:

  { path: 'dashboard', component: DashboardComponent, canActivate: [AuthGuard] },
  { path: 'announcements', component: AnnouncementsComponent, canActivate: [AuthGuard] },
  { path: 'classes', component: ClassesComponent, canActivate: [AuthGuard] },
  { path: 'forums', component: ForumsComponent, canActivate: [AuthGuard] },
  { path: 'student-list', component: StudentListComponent, canActivate: [AuthGuard] },
  { path: 'profile', component: ProfileComponent, canActivate: [AuthGuard] },
  { path: '', redirectTo: 'dashboard', pathMatch: 'full', canActivate: [AuthGuard] },
];

@NgModule({
  imports: [RouterModule.forChild(MainRoutes)],
  exports: [RouterModule],
})
export class MainRoutingModule {}

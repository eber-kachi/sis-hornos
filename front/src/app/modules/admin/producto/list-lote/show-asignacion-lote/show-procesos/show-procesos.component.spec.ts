import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowProcesosComponent } from './show-procesos.component';

describe('ShowProcesosComponent', () => {
  let component: ShowProcesosComponent;
  let fixture: ComponentFixture<ShowProcesosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShowProcesosComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ShowProcesosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

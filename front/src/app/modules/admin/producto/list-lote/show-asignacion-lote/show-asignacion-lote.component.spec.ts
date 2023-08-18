import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowAsignacionLoteComponent } from './show-asignacion-lote.component';

describe('ShowAsignacionLoteComponent', () => {
  let component: ShowAsignacionLoteComponent;
  let fixture: ComponentFixture<ShowAsignacionLoteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShowAsignacionLoteComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ShowAsignacionLoteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

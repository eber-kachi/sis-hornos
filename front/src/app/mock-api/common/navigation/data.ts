/* tslint:disable:max-line-length */
import {FuseNavigationItem} from '@fuse/components/navigation';


export const defaultNavigation: FuseNavigationItem[] = [
 /* {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example',
    roles: ['admin','ayudante', 'ayudante experto', 'jefe contratos' ]
  }, */
  {
    id: 'lista-rol',
    title: 'Roles',
    type: 'basic',
    icon: 'heroicons_outline:clipboard-list',
    link: '/lista-rol',
    roles: ['admin']
    
  },
 

  {
    id: 'list-grupo-personal',
    title: 'Grupo Personal',
    type: 'basic',
    icon: 'heroicons_outline:user-group',
    link: '/lista-grupo',
    roles: ['admin']
  },
  {
    id: 'lista-personal',
    title: 'Personal',
    type: 'basic',
    icon: 'heroicons_outline:users',
    link: '/lista-personal',
    roles: ['admin', 'jefe contratos']
  },
  {
    id: 'lista-pedidos',
    title: 'Pedidos',
    type: 'basic',
    icon: 'heroicons_outline:shopping-bag',
    link: '/lista-pedidos',
    roles: ['admin']
  },
  {
    id: 'lista-lote',
    title: 'Lotes Produccion',
    type: 'basic',
    icon: 'heroicons_outline:view-boards',
    link: '/lista-lote',
    roles: ['admin', 'jefe contratos']
  },
  {
    id: 'lista-material',
    title: 'Materiales',
    type: 'basic',
    icon: 'heroicons_outline:view-grid-add',
    link: '/lista-material',
    roles: ['admin']
  },
  {
    id: 'lista-pruducto',
    title: 'Productos',
    type: 'basic',
    icon: 'heroicons_outline:cube-transparent',
    link: '/lista-producto',
    roles: ['admin', 'jefe contratos']
  },
  {
    id: 'lista-departamento',
    title: 'Departamentos',
    type: 'basic',
    icon: 'heroicons_outline:filter',
    link: '/lista-departamento',
    roles: ['admin']
  },
  {
    id: 'lista-cliente',
    title: 'Clientes',
    type: 'basic',
    icon: 'heroicons_outline:identification',
    link: '/lista-cliente',
    roles: ['admin']
  },
  {
    id: 'calendario',
    title: 'Cronograma',
    type: 'basic',
    icon: 'heroicons_outline:calendar',
    link: '/cronograma',
    roles: ['admin','ayudante', 'ayudante experto', 'jefe contratos' ]
  },

];





export const compactNavigation: FuseNavigationItem[] = [
  {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example'
  }
];
export const futuristicNavigation: FuseNavigationItem[] = [
  {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example'
  }
];
export const horizontalNavigation: FuseNavigationItem[] = [
  {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example',
  }
];


